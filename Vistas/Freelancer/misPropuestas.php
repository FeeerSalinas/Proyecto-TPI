<?php
session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

include '../Menu/header.php';
include '../Menu/navbarFreelancer.php';
include '../Menu/sidebarFreelancer.php';

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
    // Obtener todas las propuestas del freelancer
    $query = "SELECT p.titulo, pr.descripcion, pr.montoPropuesto, pr.estado, pr.fechaEnvio
              FROM propuesta pr
              JOIN proyectos p ON pr.idProyecto = p.idProyecto
              WHERE pr.idFreelancer = :idFreelancer";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idFreelancer', $idFreelancer, PDO::PARAM_INT);
    $stmt->execute();
    $propuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Propuestas</title>
    <link rel="stylesheet" href="../../CSS/misPropuestas.css"> <!-- Usamos el CSS para freelancers -->
</head>

<body>
    <div class="content" id="content">
        <main class="p-4">
            <div class="container mt-4">
                <h2 class="mb-4">Mis Propuestas</h2>

                <?php if (!empty($propuestas)): ?>
                    <div class="propuestas-list">
                        <?php foreach ($propuestas as $propuesta): ?>
                            <div class="propuesta-card">
                                <div class="propuesta-header">
                                    <h3><?php echo htmlspecialchars($propuesta['titulo']); ?></h3>
                                    <span class="badge <?php echo getBadgeClass($propuesta['estado']); ?>">
                                        <?php echo htmlspecialchars($propuesta['estado']); ?>
                                    </span>
                                </div>
                                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($propuesta['descripcion']); ?></p>
                                <p><strong>Monto Propuesto:</strong> $<?php echo number_format($propuesta['montoPropuesto'], 2); ?></p>
                                <p><strong>Fecha de Envío:</strong> <?php echo date('d/m/Y', strtotime($propuesta['fechaEnvio'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-propuestas">No has enviado ninguna propuesta aún.</p>
                <?php endif; ?>
            </div>
        </main>

    </div>
</body>

</html>

<?php include '../Menu/footer.php'; ?>

<?php
function getBadgeClass($estado)
{
    switch (strtolower($estado)) {
        case 'pendiente':
            return 'badge-warning';
        case 'aceptada':
            return 'badge-success';
        case 'rechazada':
            return 'badge-danger';
        default:
            return 'badge-secondary';
    }
}
?>