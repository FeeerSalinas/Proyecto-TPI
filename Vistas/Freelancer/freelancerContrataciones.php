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
        c.idContrato, c.fechaContratacion, c.fechaInicio, c.fechaFin, c.pago, 
        c.estado, c.metodo, 
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
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root {
    --primary-color: #0070ba;
    --primary-dark: #003087;
    --success-color: #10B981;
    --warning-color: #F59E0B;
    --danger-color: #dc3545;
    --gray-bg: #F9FAFB;
    --card-border-radius: 1rem;
    --transition-speed: 0.3s;
}

body {
    background-color: var(--gray-bg);
    font-family: 'Inter', 'Arial', sans-serif;
}

h1 {
    color: var(--primary-dark);
    font-weight: 600;
    margin-bottom: 2rem;
    text-align: center;
}

.card {
    border: none;
    border-radius: var(--card-border-radius);
    overflow: hidden;
    transition: transform var(--transition-speed), box-shadow var(--transition-speed);
    background: white;
    margin-bottom: 1rem;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.card-body {
    padding: 1.5rem;
}

.card-title {
    color: var(--primary-color);
    font-size: 1.25rem;
    font-weight: 600;
    border-bottom: 2px solid var(--gray-bg);
    padding-bottom: 0.75rem;
}

.card-footer {
    background-color: #F8FAFC;
    border-top: 1px solid #E2E8F0;
    padding: 1rem;
    text-align: center;
}

.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
    border-radius: 2rem;
    font-size: 0.875rem;
}

.badge.bg-warning {
    background-color: #FEF3C7 !important;
    color: #92400E !important;
}

.badge.bg-success {
    background-color: #D1FAE5 !important;
    color: #065F46 !important;
}

.badge.bg-danger {
    background-color: #FEE2E2 !important;
    color: #991B1B !important;
}

.alert-info {
    background-color: #EFF6FF;
    border: 1px solid #BFDBFE;
    color: #1E40AF;
    border-radius: 0.75rem;
    padding: 1.5rem;
}

.btn {
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    transition: all var(--transition-speed);
}

.btn-success {
    background-color: var(--success-color);
    border: none;
    color: white;
}

.btn-success:hover {
    background-color: #059669;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.btn-danger {
    background-color: var(--danger-color);
    border: none;
}

.btn-danger:hover {
    background-color: #bb2d3b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
}

@media (max-width: 768px) {
    .card-body, .card-footer {
        text-align: center;
    }
    .btn {
        width: 100%;
    }
}
</style>
</head>

<div class="content" id="content">
    <h1 class="text-center mb-5">Propuestas de contratistas</h1>

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
                            <p><strong>Método de Pago:</strong> <?= htmlspecialchars($contratacion['metodo']) ?></p>
                            <p><strong>Monto de Pago:</strong> $<?= number_format($contratacion['pago'], 2) ?></p>
                            <p><strong>Fecha de Inicio:</strong> 
                                <?= date('d/m/Y', strtotime($contratacion['fechaInicio'])) ?>
                            </p>
                            <p><strong>Fecha de Fin:</strong> 
                                <?= date('d/m/Y', strtotime($contratacion['fechaFin'])) ?>
                            </p>
                            <p><strong>Fecha de Contratación:</strong> 
                                <?= date('d/m/Y', strtotime($contratacion['fechaContratacion'])) ?>
                            </p>
                            
                        </div>

                        <!-- Mostrar botones solo si el estado es 'Pendiente' -->
                        <?php if ($contratacion['estado'] === 'Pendiente'): ?>
                            <div class="card-footer text-end">
                                <form method="POST" action="../../Controladores/procesarEstado.php" class="d-inline">
                                    <input type="hidden" name="idContrato" value="<?= $contratacion['idContrato'] ?>">
                                    <button 
                                        type="submit" 
                                        name="estado" 
                                        value="Aceptada" 
                                        class="btn btn-success"
                                    >
                                        Aceptar
                                    </button>
                                </form>
                                <form method="POST" action="../../Controladores/procesarEstado.php" class="d-inline">
                                    <input type="hidden" name="idContrato" value="<?= $contratacion['idContrato'] ?>">
                                    <button 
                                        type="submit" 
                                        name="estado" 
                                        value="Rechazada" 
                                        class="btn btn-danger"
                                    >
                                        Rechazar
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="card-footer text-center">
                                <span class="badge <?= getBadgeClass($contratacion['estado']) ?> fs-5">
                                    <?= htmlspecialchars($contratacion['estado']) ?>
                                </span>
                            </div>
                        <?php endif; ?>
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
// Función para determinar la clase del badge según el estado
function getBadgeClass($estado) {
    switch (strtolower($estado)) {
        case 'pendiente':
            return 'badge bg-warning text-dark';  // Clase Bootstrap para amarillo
        case 'aceptada':
            return 'badge bg-success';  // Clase Bootstrap para verde
        case 'rechazada':
            return 'badge bg-danger';  // Clase Bootstrap para rojo
        default:
            return 'badge bg-secondary';  // Clase Bootstrap para gris
    }
}
?>

