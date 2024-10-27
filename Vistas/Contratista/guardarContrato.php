<?php
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'contratista') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

require_once('../../Modelos/ConnectionDB.php');
$connection = new ConnectionDB();
$conn = $connection->getConnectionDB();

try {
    // Iniciar transacción
    $conn->beginTransaction();
    
    // Obtener datos del formulario
    $idContratista = $_SESSION['idUsuario'];
    $idProyecto = $_POST['idProyecto'];
    $idFreelancer = $_POST['idFreelancer'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $fechaContratacion = date('Y-m-d');
    $pago = $_POST['pago'];
    $metodo = $_POST['metodo'];
    $estado = 'generado';
    
    // Insertar el contrato
    $query = "INSERT INTO contrataciones (
                idProyecto, 
                idServicio,
                idContratista, 
                idFreelancer, 
                estado, 
                metodo, 
                fechaInicio, 
                fechaFin, 
                fechaContratacion, 
                pago
            ) VALUES (
                :idProyecto, 
                NULL,
                :idContratista, 
                :idFreelancer, 
                :estado, 
                :metodo, 
                :fechaInicio, 
                :fechaFin, 
                :fechaContratacion, 
                :pago
            )";
            
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':idProyecto' => $idProyecto,
        ':idContratista' => $idContratista,
        ':idFreelancer' => $idFreelancer,
        ':estado' => $estado,
        ':metodo' => $metodo,
        ':fechaInicio' => $fechaInicio,
        ':fechaFin' => $fechaFin,
        ':fechaContratacion' => $fechaContratacion,
        ':pago' => $pago
    ]);
    
    // Actualizar el estado del proyecto a 'en progreso'
    $queryProyecto = "UPDATE proyectos SET estado = 'en progreso' WHERE idProyecto = :idProyecto";
    $stmtProyecto = $conn->prepare($queryProyecto);
    $stmtProyecto->execute([':idProyecto' => $idProyecto]);
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Contrato generado exitosamente'
    ]);
    
} catch(PDOException $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Error al generar el contrato: ' . $e->getMessage()
    ]);
}