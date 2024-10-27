<?php
session_start();
require_once('../../Modelos/ConnectionDB.php');

if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'contratista') {
    header('Location: ../login.php');
    exit();
}

$idContratacion = filter_input(INPUT_GET, 'idContratacion', FILTER_VALIDATE_INT);

if (!$idContratacion) {
    header('Location: misProyectos.php?error=pago_invalido');
    exit();
}

try {
    $connection = new ConnectionDB();
    $conn = $connection->getConnectionDB();
    
    // Verificar el estado del pago
    $query = "SELECT p.estado as estado_pago, c.pago as monto
              FROM contrataciones c
              LEFT JOIN pagos p ON c.idContrato = p.idContratacion
              WHERE c.idContrato = :idContratacion 
              AND c.idContratista = :idContratista";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idContratacion', $idContratacion, PDO::PARAM_INT);
    $stmt->bindParam(':idContratista', $_SESSION['idUsuario'], PDO::PARAM_INT);
    $stmt->execute();
    
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    header('Location: misProyectos.php?error=error_sistema');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pago</title>
    <link rel="stylesheet" href="../../CSS/styles.css">
</head>
<body>
    <?php
    include '../Menu/header.php';
    include '../Menu/navbarContratista.php';
    include '../Menu/sidebarContratista.php';
    ?>
    
    <div class="content">
        <div class="container mt-4">
            <div class="card">
                <div class="card-body text-center">
                    <h2>Estado del Pago</h2>
                    
                    <?php if ($resultado && $resultado['estado_pago'] == 'completado'): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h3>¡Pago Completado!</h3>
                            <p>El pago de $<?php echo number_format($resultado['monto'], 2); ?> se ha procesado correctamente.</p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <h3>Pago en Proceso</h3>
                            <p>El pago está siendo procesado. Por favor, espere unos minutos y actualice la página.</p>
                        </div>
                    <?php endif; ?>
                    
                    <a href="misProyectos.php" class="btn btn-primary mt-3">
                        Volver a Mis Proyectos
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../Menu/footer.php'; ?>
</body>
</html>