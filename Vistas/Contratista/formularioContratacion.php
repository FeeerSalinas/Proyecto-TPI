<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['tipoUsuario'])) {
    header("Location: ../login.php");
    exit();
}

// Verificar si el usuario es un contratista
if ($_SESSION['tipoUsuario'] !== 'contratista') {
    header("Location: ../unauthorized.php");
    exit();
}

require_once('../../Modelos/ConnectionDB.php');
$connection = new ConnectionDB();
$conn = $connection->getConnectionDB();

// Obtener datos de la propuesta y el proyecto
if (isset($_GET['idPropuesta'])) {
    $idPropuesta = $_GET['idPropuesta'];
    
    try {
        $query = "SELECT pr.*, p.*, u.nombre as freelancer_nombre 
                 FROM propuesta pr 
                 JOIN proyectos p ON pr.idProyecto = p.idProyecto 
                 JOIN usuarios u ON pr.idFreelancer = u.idUsuario 
                 WHERE pr.idPropuesta = :idPropuesta";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':idPropuesta', $idPropuesta, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$datos) {
            header("Location: misProyectos.php");
            exit();
        }
    } catch(PDOException $e) {
        die("Error en la consulta: " . $e->getMessage());
    }
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->beginTransaction();
        
        // Insertar en la tabla contrataciones
        $query = "INSERT INTO contrataciones (
                    idProyecto, idContratista, idFreelancer, 
                    estado, metodo, fechaInicio, fechaFin, 
                    fechaContratacion, pago
                ) VALUES (
                    :idProyecto, :idContratista, :idFreelancer,
                    'pendiente', 'PayPal', :fechaInicio, :fechaFin,
                    CURDATE(), :pago
                )";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':idProyecto', $datos['idProyecto']);
        $stmt->bindParam(':idContratista', $_SESSION['idUsuario']);
        $stmt->bindParam(':idFreelancer', $datos['idFreelancer']);
        $stmt->bindParam(':fechaInicio', $_POST['fechaInicio']);
        $stmt->bindParam(':fechaFin', $_POST['fechaFin']);
        $stmt->bindParam(':pago', $_POST['monto']);
        $stmt->execute();
        
        // Actualizar el estado del proyecto
        $queryProyecto = "UPDATE proyectos SET estado = 'en_proceso' WHERE idProyecto = :idProyecto";
        $stmt = $conn->prepare($queryProyecto);
        $stmt->bindParam(':idProyecto', $datos['idProyecto']);
        $stmt->execute();
        
        $conn->commit();
        header("Location: misProyectos.php?success=1");
        exit();
        
    } catch(PDOException $e) {
        $conn->rollBack();
        $error = "Error al crear el contrato: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Contrato</title>
    <link rel="stylesheet" href="../../CSS/formularioContrato.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Crear Contrato</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5>Detalles del Proyecto</h5>
                <p><strong>Título:</strong> <?php echo htmlspecialchars($datos['titulo']); ?></p>
                <p><strong>Freelancer:</strong> <?php echo htmlspecialchars($datos['freelancer_nombre']); ?></p>
                <p><strong>Monto propuesto:</strong> $<?php echo number_format($datos['montoPropuesto'], 2); ?></p>
            </div>
        </div>

        <form method="POST" class="needs-validation" novalidate>
            <!-- Campo de monto editable -->
            <div class="form-group">
                <label for="monto">Monto del contrato ($):</label>
                <input type="number" 
                       class="form-control" 
                       id="monto" 
                       name="monto" 
                       step="0.01" 
                       value="<?php echo $datos['montoPropuesto']; ?>" 
                       required>
                <div class="invalid-feedback">
                    Por favor ingrese un monto válido.
                </div>
            </div>

            <div class="form-group">
                <label>Método de pago:</label>
                <input type="text" class="form-control" value="PayPal" readonly>
                <small class="text-muted">El pago se procesará a través de PayPal</small>
            </div>

            <div class="form-group">
                <label for="fechaInicio">Fecha de inicio:</label>
                <input type="date" 
                       class="form-control" 
                       id="fechaInicio" 
                       name="fechaInicio" 
                       required>
                <div class="invalid-feedback">
                    Por favor seleccione una fecha de inicio.
                </div>
            </div>

            <div class="form-group">
                <label for="fechaFin">Fecha de finalización:</label>
                <input type="date" 
                       class="form-control" 
                       id="fechaFin" 
                       name="fechaFin" 
                       required>
                <div class="invalid-feedback">
                    Por favor seleccione una fecha de finalización.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Crear Contrato</button>
            <a href="misProyectos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
    // Validación del formulario
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const montoInput = document.getElementById('monto');
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            // Validación adicional del monto
            const monto = parseFloat(montoInput.value);
            if (isNaN(monto) || monto <= 0) {
                event.preventDefault();
                montoInput.setCustomValidity('Por favor ingrese un monto válido mayor a 0');
            } else {
                montoInput.setCustomValidity('');
            }
            
            form.classList.add('was-validated');
        });

        // Validar que la fecha de fin sea posterior a la fecha de inicio
        const fechaInicio = document.getElementById('fechaInicio');
        const fechaFin = document.getElementById('fechaFin');

        fechaInicio.addEventListener('change', validarFechas);
        fechaFin.addEventListener('change', validarFechas);

        function validarFechas() {
            if (fechaInicio.value && fechaFin.value) {
                if (fechaFin.value < fechaInicio.value) {
                    fechaFin.setCustomValidity('La fecha de finalización debe ser posterior a la fecha de inicio');
                } else {
                    fechaFin.setCustomValidity('');
                }
            }
        }
        
        // Establecer fecha mínima como hoy
        const today = new Date().toISOString().split('T')[0];
        fechaInicio.min = today;
        fechaFin.min = today;
        
        // Validación del monto al cambiar
        montoInput.addEventListener('input', function() {
            const monto = parseFloat(this.value);
            if (isNaN(monto) || monto <= 0) {
                this.setCustomValidity('Por favor ingrese un monto válido mayor a 0');
            } else {
                this.setCustomValidity('');
            }
        });
    });
    </script>
</body>
</html>