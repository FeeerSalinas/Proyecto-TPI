<?php
include '../Menu/header.php';
include '../Menu/sidebarFreelancer.php';
include '../Menu/navbarFreelancer.php';

require_once('../../Modelos/ConnectionDB.php');
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'freelancer') {
    header("Location: ../login.php");
    exit();
}

$idProyecto = $_GET['idProyecto'] ?? null;  // Obtener ID del proyecto seleccionado

$idFreelancer = $_SESSION['idUsuario'];
$conexion = new ConnectionDB();
$conn = $conexion->getConnectionDB();

try {
    $query = "SELECT idProyecto, titulo FROM proyectos WHERE estado IN ('publicado', 'En proceso', 'activo')";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>console.log('Error: " . $e->getMessage() . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Propuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .content {
            padding: 2rem;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #374151;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        textarea, select {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            margin-top: 0.5rem;
        }

        .guardar {
            background-color: #4F46E5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
        }

        .guardar:hover {
            background-color: #6366F1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .regresar {
            background-color: #e5e7eb;
            color: #374151;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            border: 1px solid #d1d5db;
        }

        .regresar:hover {
            background-color: #d1d5db;
        }
    </style>
</head>

<body>
    <div class="content" id="content">
        <main>
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Crear Propuesta</h1>
                    <a href="BuscarProyectos.php" class="regresar">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                </div>

                <?php if (!empty($proyectos)): ?>
                    <form id="propuestaForm" action="procesar_propuesta.php" method="POST">
                        <div class="form-group">
                            <label for="proyecto">Selecciona el Proyecto</label>
                            <select id="proyecto" name="proyecto" required>
                                <option value="">Selecciona un proyecto</option>
                                <?php foreach ($proyectos as $proyecto): 
                                    $selected = ($proyecto['idProyecto'] == $idProyecto) ? 'selected' : ''; ?>
                                    <option value="<?= $proyecto['idProyecto'] ?>" <?= $selected ?>>
                                        <?= htmlspecialchars($proyecto['titulo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción de la Propuesta</label>
                            <textarea id="descripcion" name="descripcion" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="monto">Monto Propuesto</label>
                            <input type="number" id="monto" name="monto" step="0.01" min="0" required class="form-control">
                        </div>

                        <button class="guardar" type="submit">Enviar Propuesta</button>
                    </form>
                <?php else: ?>
                    <div class="text-center mt-5">
                        <p>No hay proyectos disponibles para enviar una propuesta en este momento.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include '../Menu/footer.php'; ?>
