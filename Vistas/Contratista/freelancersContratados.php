<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'contratista') {
    header("Location: ../index.php");
    exit();
}

include '../Menu/header.php';
include '../Menu/navbarContratista.php';
include '../Menu/sidebarContratista.php';

require_once '../../Modelos/ConnectionDB.php';
$connection = new ConnectionDB();
$conn = $connection->getConnectionDB();

// Obtener el ID del contratista
$idContratista = $_SESSION['idUsuario'];

try {
    $query = "
        SELECT 
            c.idContrato, c.fechaContratacion, c.estado, c.metodo, 
            s.titulo AS servicio_titulo, s.descripcion AS servicio_descripcion,
            f.nombre AS freelancer_nombre, f.correo AS freelancer_correo, 
            c.pago AS montoPago
        FROM contratacionesf c
        JOIN servicios s ON c.idServicio = s.idServicio
        JOIN usuarios f ON c.idFreelancer = f.idUsuario
        WHERE c.idContratista = :idContratista
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idContratista', $idContratista, PDO::PARAM_INT);
    $stmt->execute();
    $contrataciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<p>Error al obtener las contrataciones: " . $e->getMessage() . "</p>";
    exit();
}
?>

<div class="content" id="content">
    <a href="BuscarFreelancer.php" class="btn btn-secondary mb-4">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>

    <h1 class="text-center mb-5">Freelancers Contratados</h1>

    <?php if (!empty($contrataciones)): ?>
        <div class="row">
            <?php foreach ($contrataciones as $contratacion): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <?= htmlspecialchars($contratacion['freelancer_nombre']) ?>
                            </h5>
                            <p><strong>Servicio:</strong> <?= htmlspecialchars($contratacion['servicio_titulo']) ?></p>
                            <p><strong>Descripción:</strong> <?= htmlspecialchars($contratacion['servicio_descripcion']) ?></p>
                            <p><strong>Correo:</strong> 
                                <a href="mailto:<?= htmlspecialchars($contratacion['freelancer_correo']) ?>">
                                    <?= htmlspecialchars($contratacion['freelancer_correo']) ?>
                                </a>
                            </p>
                            <p><strong>Método de pago:</strong> <?= htmlspecialchars($contratacion['metodo']) ?></p>
                            <p><strong>Estado:</strong> 
                                <span class="<?= getBadgeClass($contratacion['estado']) ?>">
                                    <?= htmlspecialchars($contratacion['estado']) ?>
                                </span>
                            </p>
                            <p><strong>Fecha de Contratación:</strong> 
                                <?= date('d/m/Y', strtotime($contratacion['fechaContratacion'])) ?>
                            </p>
                            <p><strong>Monto a Pagar:</strong> $<?= htmlspecialchars($contratacion['montoPago']) ?></p>
                        </div>

                        <div class="card-footer text-end">
                            <?php if ($contratacion['estado'] === 'Aceptada'): ?>
                                <!-- Botón de PayPal habilitado -->
                                <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                    <input type="hidden" name="cmd" value="_xclick">
                                    <input type="hidden" name="business" value="tucorreo@paypal.com">
                                    <input type="hidden" name="item_name" value="<?= htmlspecialchars($contratacion['servicio_titulo']) ?>">
                                    <input type="hidden" name="amount" value="<?= htmlspecialchars($contratacion['montoPago']) ?>">
                                    <input type="hidden" name="currency_code" value="USD">
                                    <input type="hidden" name="return" value="https://tu-sitio.com/exito">
                                    <input type="hidden" name="cancel_return" value="https://tu-sitio.com/cancelado">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fab fa-paypal"></i> Pagar con PayPal
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>No disponible</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No has contratado a ningún freelancer aún.
        </div>
    <?php endif; ?>
</div>

<?php include '../Menu/footer.php'; ?>

<?php
// Función para determinar la clase del badge del estado
function getBadgeClass($estado) {
    switch (strtolower($estado)) {
        case 'pendiente':
            return 'badge bg-warning text-dark';  // Amarillo
        case 'aceptada':
            return 'badge bg-success';  // Verde
        case 'rechazada':
            return 'badge bg-danger';  // Rojo
        default:
            return 'badge bg-secondary';  // Gris
    }
}
?>
