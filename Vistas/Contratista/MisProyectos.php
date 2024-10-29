<?php
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'contratista') {
    header("Location: ../login.php");
    exit();
}

require_once('../../Modelos/ConnectionDB.php');
$connection = new ConnectionDB();
$conn = $connection->getConnectionDB();

include '../Menu/header.php';
include '../Menu/navbarContratista.php';
include '../Menu/sidebarContratista.php';

$idContratista = $_SESSION['idUsuario'];

try {
    $query = "SELECT p.*, c.nombre AS categoria_nombre 
              FROM proyectos p 
              LEFT JOIN categoria c ON p.idCategoria = c.idCategoria 
              WHERE p.idContratista = :idContratista";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idContratista', $idContratista, PDO::PARAM_INT);
    $stmt->execute();
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
        }

        .content {
            padding: 2rem;
        }

        .proyecto-card {
            border-radius: 0.75rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .proyecto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .proyecto-header {
            background-color: #4F46E5;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem 0.75rem 0 0;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 1.5rem;
            font-weight: 600;
        }

        .badge-en-proceso {
            background-color: #fcd34d;
            color: #92400e;
        }

        .propuesta-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.75rem;
        }

        .no-propuestas {
            text-align: center;
            font-size: 1rem;
            color: #6b7280;
        }

        .propuesta-actions button {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="content" id="content">
        <div class="container mt-4">
            <h2 class="mb-4">Mis Proyectos</h2>

            <?php if (!empty($proyectos)): ?>
                <?php foreach ($proyectos as $proyecto): ?>
                    <div class="proyecto-card">
                        <div class="proyecto-header">
                            <?= htmlspecialchars($proyecto['titulo']) ?>
                            <span class="badge <?= getBadgeClass($proyecto['estado']) ?>">
                                <?= htmlspecialchars($proyecto['estado']) ?>
                            </span>
                        </div>
                        
                        <div class="p-4">
                            <p><strong>Categoría:</strong> <?= htmlspecialchars($proyecto['categoria_nombre']) ?></p>
                            <p><strong>Presupuesto:</strong> $<?= number_format($proyecto['presupuesto'], 2) ?></p>
                            <p><strong>Fecha de publicación:</strong> <?= date('d/m/Y', strtotime($proyecto['fechaPublicación'])) ?></p>
                            <p class="descripcion"><?= htmlspecialchars($proyecto['descripcion']) ?></p>

                            <div class="propuestas-section">
                                <h4>Propuestas recibidas</h4>

                                <?php
                                $queryPropuestas = "SELECT pr.*, u.nombre AS freelancer_nombre 
                                                    FROM propuesta pr 
                                                    JOIN usuarios u ON pr.idFreelancer = u.idUsuario 
                                                    WHERE pr.idProyecto = :idProyecto";
                                $stmtPropuestas = $conn->prepare($queryPropuestas);
                                $stmtPropuestas->bindParam(':idProyecto', $proyecto['idProyecto'], PDO::PARAM_INT);
                                $stmtPropuestas->execute();
                                $propuestas = $stmtPropuestas->fetchAll(PDO::FETCH_ASSOC);
                                ?>

                                <?php if (!empty($propuestas)): ?>
                                    <?php foreach ($propuestas as $propuesta): ?>
                                        <div class="propuesta-card">
                                            <div class="d-flex justify-content-between">
                                                <h5><?= htmlspecialchars($propuesta['freelancer_nombre']) ?></h5>
                                                <span class="badge <?= getBadgeClass($propuesta['estado']) ?>">
                                                    <?= htmlspecialchars($propuesta['estado']) ?>
                                                </span>
                                            </div>
                                            <p><strong>Monto propuesto:</strong> $<?= number_format($propuesta['montoPropuesto'], 2) ?></p>
                                            <p><?= htmlspecialchars($propuesta['descripcion']) ?></p>
                                            <div class="propuesta-actions">
                                                <?php if ($propuesta['estado'] === 'pendiente'): ?>
                                                    <button class="btn btn-success btn-sm">Aceptar</button>
                                                    <button class="btn btn-danger btn-sm">Rechazar</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="no-propuestas">Aún no hay propuestas para este proyecto.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">No tienes proyectos creados aún.</div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include '../Menu/footer.php';

function getBadgeClass($estado) {
    switch (strtolower($estado)) {
        case 'activo': return 'badge-success';
        case 'pendiente': return 'badge-warning';
        case 'en proceso': return 'badge-en-proceso';
        case 'finalizado': return 'badge-primary';
        case 'cancelado': return 'badge-danger';
        case 'rechazada': return 'badge-danger';
        case 'aceptada': return 'badge-success';
        default: return 'badge-secondary';
    }
}
?>
