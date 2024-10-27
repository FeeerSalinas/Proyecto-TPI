<?php

session_start();
require_once('../../Modelos/ConnectionDB.php');

// Configurar headers
header('Content-Type: application/json');

// Verificar autenticación
if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'contratista') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Validar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    $connection = new ConnectionDB();
    $conn = $connection->getConnectionDB();
    
    // Obtener y validar datos del formulario
    $idPropuesta = filter_input(INPUT_POST, 'idPropuesta', FILTER_VALIDATE_INT);
    $idProyecto = filter_input(INPUT_POST, 'idProyecto', FILTER_VALIDATE_INT);
    $idFreelancer = filter_input(INPUT_POST, 'idFreelancer', FILTER_VALIDATE_INT);
    $fechaInicio = filter_input(INPUT_POST, 'fechaInicio', FILTER_SANITIZE_STRING);
    $fechaFin = filter_input(INPUT_POST, 'fechaFin', FILTER_SANITIZE_STRING);
    $metodo = filter_input(INPUT_POST, 'metodo', FILTER_SANITIZE_STRING);
    
    if (!$idPropuesta || !$idProyecto || !$idFreelancer || !$fechaInicio || !$fechaFin || !$metodo) {
        throw new Exception('Datos incompletos o inválidos');
    }

    // Iniciar transacción
    $conn->beginTransaction();
    
    // Obtener el monto de la propuesta
    $queryPropuesta = "SELECT montoPropuesto FROM propuesta WHERE idPropuesta = :idPropuesta";
    $stmtPropuesta = $conn->prepare($queryPropuesta);
    $stmtPropuesta->bindParam(':idPropuesta', $idPropuesta, PDO::PARAM_INT);
    $stmtPropuesta->execute();
    $propuesta = $stmtPropuesta->fetch(PDO::FETCH_ASSOC);
    
    if (!$propuesta) {
        throw new Exception('Propuesta no encontrada');
    }
    
    // Insertar contrato
    $queryContrato = "INSERT INTO contrataciones (
        idProyecto, idContratista, idFreelancer, estado, metodo, 
        fechaInicio, fechaFin, fechaContratacion, pago
    ) VALUES (
        :idProyecto, :idContratista, :idFreelancer, 'pendiente', :metodo,
        :fechaInicio, :fechaFin, CURRENT_DATE, :pago
    )";
    
    $stmtContrato = $conn->prepare($queryContrato);
    $stmtContrato->bindParam(':idProyecto', $idProyecto, PDO::PARAM_INT);
    $stmtContrato->bindParam(':idContratista', $_SESSION['idUsuario'], PDO::PARAM_INT);
    $stmtContrato->bindParam(':idFreelancer', $idFreelancer, PDO::PARAM_INT);
    $stmtContrato->bindParam(':metodo', $metodo, PDO::PARAM_STR);
    $stmtContrato->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
    $stmtContrato->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
    $stmtContrato->bindParam(':pago', $propuesta['montoPropuesto'], PDO::PARAM_STR);
    
    $stmtContrato->execute();
    
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Contrato generado exitosamente']);
    
} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}