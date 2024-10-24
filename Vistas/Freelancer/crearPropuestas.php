<?php
include '../Menu/header.php';   // Header con estilos
include '../Menu/navbar.php';   // Navbar superior
include '../Menu/sidebar.php';  // Sidebar izquierdo

require_once('../../Modelos/ConnectionDB.php');
session_start();

// Verificar si el usuario está autenticado y es un freelancer
if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'freelancer') {
    header("Location: ../login.php");
    exit();
}

$idFreelancer = $_SESSION['idUsuario'];
$conexion = new ConnectionDB();
$conn = $conexion->getConnectionDB();

try {
    // Obtener proyectos disponibles
    $query = "SELECT idProyecto, titulo FROM proyectos WHERE estado IN ('publicado', 'En proceso', 'activo')";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($proyectos)) {
        $proyectos = [];
    }
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
    <link rel="stylesheet" href="../../CSS/crearPropuestas.css"> <!-- Usamos el CSS para freelancers -->
</head>
<body>
<div class="content" id="content">
    <main class="p-4">
        <div class="container">
        <h1>Crear Propuesta</h1>

        <?php if (!empty($proyectos)): ?>
        <form id="propuestaForm" action="procesar_propuesta.php" method="POST">
            <div class="form-group">
                <label for="proyecto">Selecciona el Proyecto</label>
                <select id="proyecto" name="proyecto" required>
                    <option value="">Selecciona un proyecto</option>
                    <?php foreach ($proyectos as $proyecto): ?>
                        <option value="<?php echo $proyecto['idProyecto']; ?>">
                            <?php echo htmlspecialchars($proyecto['titulo']); ?>
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
                <input type="number" id="monto" name="monto" step="0.01" required>
            </div>

            <button class="guardar" type="submit">Enviar Propuesta</button>
        </form>
        <?php else: ?>
            <p>No hay proyectos disponibles para enviar una propuesta en este momento.</p>
        <?php endif; ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include '../Menu/footer.php'; ?>
