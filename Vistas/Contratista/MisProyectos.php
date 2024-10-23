<?php
    session_start();

    // Verificar si el usuario está autenticado
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

    include '../Menu/header.php';
    include '../Menu/navbarContratista.php';
    include '../Menu/sidebarContratista.php';

    // Obtener el ID del contratista de la sesión
    $idContratista = $_SESSION['idUsuario'];

    try {
        // Consulta para obtener los proyectos del contratista
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM proyectos p 
                  LEFT JOIN categoria c ON p.idCategoria = c.idCategoria 
                  WHERE p.idContratista = :idContratista";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':idContratista', $idContratista, PDO::PARAM_INT);
        $stmt->execute();
        $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        die("Error en la consulta: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Proyectos</title>
    <link rel="stylesheet" href="../../CSS/misProyectos.css">
</head>
<body>
    <div class="content">
        <div class="container mt-4">
            <h2 class="mb-4">Mis Proyectos</h2>
            
            <?php if(!empty($proyectos)): ?>
                <?php foreach($proyectos as $proyecto): ?>
                    <div class="proyecto-card">
                        <div class="proyecto-header">
                            <h3><?php echo htmlspecialchars($proyecto['titulo']); ?></h3>
                            <span class="badge <?php echo getBadgeClass($proyecto['estado']); ?>">
                                <?php echo htmlspecialchars($proyecto['estado']); ?>
                            </span>
                        </div>
                        
                        <div class="proyecto-info">
                            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($proyecto['categoria_nombre']); ?></p>
                            <p><strong>Presupuesto:</strong> $<?php echo number_format($proyecto['presupuesto'], 2); ?></p>
                            <p><strong>Fecha de publicación:</strong> <?php echo date('d/m/Y', strtotime($proyecto['fechaPublicación'])); ?></p>
                            <p class="descripcion"><?php echo htmlspecialchars($proyecto['descripcion']); ?></p>
                        </div>

                        <div class="propuestas-section">
                            <h4>Propuestas recibidas</h4>
                            <?php
                            try {
                                $queryPropuestas = "SELECT pr.*, u.nombre as freelancer_nombre 
                                                  FROM propuesta pr 
                                                  JOIN usuarios u ON pr.idFreelancer = u.idUsuario 
                                                  WHERE pr.idProyecto = :idProyecto";
                                
                                $stmtPropuestas = $conn->prepare($queryPropuestas);
                                $stmtPropuestas->bindParam(':idProyecto', $proyecto['idProyecto'], PDO::PARAM_INT);
                                $stmtPropuestas->execute();
                                $propuestas = $stmtPropuestas->fetchAll(PDO::FETCH_ASSOC);
                            } catch(PDOException $e) {
                                echo "Error al obtener las propuestas: " . $e->getMessage();
                                continue;
                            }
                            ?>

                            <?php if(!empty($propuestas)): ?>
                                <div class="propuestas-list">
                                    <?php foreach($propuestas as $propuesta): ?>
                                        <div class="propuesta-card">
                                            <div class="propuesta-header">
                                                <h5><?php echo htmlspecialchars($propuesta['freelancer_nombre']); ?></h5>
                                                <span class="badge <?php echo getBadgeClass($propuesta['estado']); ?>">
                                                    <?php echo htmlspecialchars($propuesta['estado']); ?>
                                                </span>
                                            </div>
                                            <p><strong>Monto propuesto:</strong> $<?php echo number_format($propuesta['montoPropuesto'], 2); ?></p>
                                            <p class="propuesta-descripcion"><?php echo htmlspecialchars($propuesta['descripcion']); ?></p>
                                            <div class="propuesta-actions">
                                                <?php if($propuesta['estado'] == 'Pendiente'): ?>
                                                    <button class="btn btn-success btn-sm" 
                                                            onclick="aceptarPropuesta(<?php echo $propuesta['idPropuesta']; ?>)">
                                                        Aceptar
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                            onclick="rechazarPropuesta(<?php echo $propuesta['idPropuesta']; ?>)">
                                                        Rechazar
                                                    </button>
                                                <?php elseif($propuesta['estado'] == 'rechazada'): ?>
                                                    <button class="btn btn-danger btn-sm"
                                                            onclick="eliminarPropuesta(<?php echo $propuesta['idPropuesta']; ?>)">
                                                        Eliminar
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="no-propuestas">Aún no hay propuestas para este proyecto.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    No tienes proyectos creados aún.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="../../JS/misProyectos.js"></script>
</body>
</html>

<?php
    include '../Menu/footer.php';

    function getBadgeClass($estado) {
        switch(strtolower($estado)) {
            case 'activo':
                return 'badge-success';
            case 'pendiente':
                return 'badge-warning';
            case 'finalizado':
                return 'badge-primary';
            case 'cancelado':
                return 'badge-danger';
            case 'rechazada':
                return 'badge-danger';
            case 'aceptada':
                return 'badge-success';
            default:
                return 'badge-secondary';
        }
    }
?>