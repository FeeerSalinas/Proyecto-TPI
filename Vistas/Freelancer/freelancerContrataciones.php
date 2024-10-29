<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'freelancer') {
    header("Location: ../index.php");
    exit();
}

include '../Menu/header.php';
include '../Menu/navbarFreelancer.php';
include '../Menu/sidebarFreelancer.php';

require_once '../../Modelos/ConnectionDB.php';
$connection = new ConnectionDB();
$conn = $connection->getConnectionDB();

// Obtener el ID del freelancer
$idFreelancer = $_SESSION['idUsuario'];

try {
    // Consultar las contrataciones realizadas al freelancer
    $query = "
        SELECT 
            c.idContrato, c.fechaContratacion, c.estado, c.metodo, 
            s.titulo AS servicio_titulo, s.descripcion AS servicio_descripcion,
            ct.nombre AS contratista_nombre, ct.correo AS contratista_correo
        FROM contratacionesf c
        JOIN servicios s ON c.idServicio = s.idServicio
        JOIN usuarios ct ON c.idContratista = ct.idUsuario
        WHERE c.idFreelancer = :idFreelancer
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idFreelancer', $idFreelancer, PDO::PARAM_INT);
    $stmt->execute();
    $contrataciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<p>Error al obtener las contrataciones: " . $e->getMessage() . "</p>";
    exit();
}
?>

<div class="content" id="content">
    <h1 class="text-center mb-5">Mis Contrataciones</h1>

    <?php if (!empty($contrataciones)): ?>
        <div class="row">
            <?php foreach ($contrataciones as $contratacion): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <?= htmlspecialchars($contratacion['contratista_nombre']) ?>
                            </h5>
                            <p><strong>Servicio:</strong> <?= htmlspecialchars($contratacion['servicio_titulo']) ?></p>
                            <p><strong>Descripción:</strong> <?= htmlspecialchars($contratacion['servicio_descripcion']) ?></p>
                            <p><strong>Correo:</strong> 
                                <a href="mailto:<?= htmlspecialchars($contratacion['contratista_correo']) ?>">
                                    <?= htmlspecialchars($contratacion['contratista_correo']) ?>
                                </a>
                            </p>
                            <p><strong>Método de Pago:</strong> <?= htmlspecialchars($contratacion['metodo']) ?></p>
                            <p><strong>Estado:</strong> 
                                <span class="<?= getBadgeClass($contratacion['estado']) ?>">
                                    <?= htmlspecialchars($contratacion['estado']) ?>
                                </span>
                            </p>
                            <p><strong>Fecha de Contratación:</strong> 
                                <?= date('d/m/Y', strtotime($contratacion['fechaContratacion'])) ?>
                            </p>
                        </div>
                        <div class="card-footer text-end">
                            <form method="POST" action="../../Controladores/procesarEstado.php" class="d-inline">
                                <input type="hidden" name="idContrato" value="<?= $contratacion['idContrato'] ?>">
                                <button 
                                    type="submit" 
                                    name="estado" 
                                    value="Aceptada" 
                                    class="btn btn-success"
                                    <?= $contratacion['estado'] !== 'Pendiente' ? 'disabled' : '' ?>
                                >
                                    Aceptar
                                </button>
                            </form>
                            <form method="POST" action="procesarEstado.php" class="d-inline">
                                <input type="hidden" name="idContrato" value="<?= $contratacion['idContrato'] ?>">
                                <button 
                                    type="submit" 
                                    name="estado" 
                                    value="Rechazada" 
                                    class="btn btn-danger"
                                    <?= $contratacion['estado'] !== 'Pendiente' ? 'disabled' : '' ?>
                                >
                                    Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No tienes contrataciones por el momento.
        </div>
    <?php endif; ?>
</div>

<?php include '../Menu/footer.php'; ?>

<?php
// Función para determinar la clase del badge del estado
function getBadgeClass($estado) {
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
