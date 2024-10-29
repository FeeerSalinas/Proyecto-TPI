<?php
// Asegurarse de que no haya output antes de este archivo
ob_start();
require_once('../../Modelos/ConnectionDB.php');
session_start();

// Configurar headers para JSON y evitar cacheo
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// Verificar si el usuario está autenticado y es un contratista
if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'contratista') {
    ob_end_clean(); // Limpiar cualquier output previo
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$idPropuesta = filter_input(INPUT_POST, 'idPropuesta', FILTER_VALIDATE_INT);
$estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
$accion = filter_input(INPUT_POST, 'accion', FILTER_SANITIZE_STRING);

if (!$idPropuesta || (!$estado && $accion !== 'eliminar')) {
    ob_end_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

try {
    $connection = new ConnectionDB();
    $conn = $connection->getConnectionDB();
    
    // Verificar que la propuesta pertenezca a un proyecto del contratista
    $query = "SELECT p.idContratista, pr.estado as estado_actual, p.idProyecto
              FROM propuesta pr 
              JOIN proyectos p ON pr.idProyecto = p.idProyecto 
              WHERE pr.idPropuesta = :idPropuesta AND p.idContratista = :idContratista";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idPropuesta', $idPropuesta, PDO::PARAM_INT);
    $stmt->bindParam(':idContratista', $_SESSION['idUsuario'], PDO::PARAM_INT);
    $stmt->execute();
    
    $propuesta = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$propuesta) {
        ob_end_clean();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Propuesta no encontrada o no autorizada']);
        exit;
    }

    // Iniciar transacción
    $conn->beginTransaction();

    if ($accion === 'eliminar') {
        if ($propuesta['estado_actual'] === 'aceptada') {
            throw new Exception('No se puede eliminar una propuesta aceptada');
        }
        
        $deleteQuery = "DELETE FROM propuesta WHERE idPropuesta = :idPropuesta";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bindParam(':idPropuesta', $idPropuesta, PDO::PARAM_INT);
        $stmt->execute();
        
        $message = 'Propuesta eliminada exitosamente';
    } else {
        // Actualizar el estado de la propuesta
        $updateQuery = "UPDATE propuesta SET estado = :estado WHERE idPropuesta = :idPropuesta";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':idPropuesta', $idPropuesta, PDO::PARAM_INT);
        $stmt->execute();
        
        // Si se acepta la propuesta
        if ($estado === 'aceptada') {
            // Actualizar estado del proyecto
            $updateProyecto = "UPDATE proyectos SET estado = 'en_progreso' WHERE idProyecto = :idProyecto";
            $stmtUpdateProyecto = $conn->prepare($updateProyecto);
            $stmtUpdateProyecto->bindParam(':idProyecto', $propuesta['idProyecto'], PDO::PARAM_INT);
            $stmtUpdateProyecto->execute();

            // Rechazar otras propuestas
            $updateOtrasPropuestas = "UPDATE propuesta 
                                    SET estado = 'rechazada' 
                                    WHERE idProyecto = :idProyecto 
                                    AND idPropuesta != :idPropuesta";
            $stmtOtrasPropuestas = $conn->prepare($updateOtrasPropuestas);
            $stmtOtrasPropuestas->bindParam(':idProyecto', $propuesta['idProyecto'], PDO::PARAM_INT);
            $stmtOtrasPropuestas->bindParam(':idPropuesta', $idPropuesta, PDO::PARAM_INT);
            $stmtOtrasPropuestas->execute();
        }
        
        $message = 'Propuesta actualizada exitosamente';
    }

    $conn->commit();
    ob_end_clean();
    echo json_encode(['success' => true, 'message' => $message]);
    
} catch (Exception $e) {
    if ($conn && $conn->inTransaction()) {
        $conn->rollBack();
    }
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
