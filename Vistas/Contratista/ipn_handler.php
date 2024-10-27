<?php
require_once('../../Modelos/ConnectionDB.php');

// Configuración de PayPal
$sandbox = true; // Cambiar a false en producción
$paypal_url = $sandbox ? 
    'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' : 
    'https://ipnpb.paypal.com/cgi-bin/webscr';

/**
 * Función para verificar la respuesta IPN con PayPal
 */
function verificarIPN($datos_post) {
    global $paypal_url;
    
    $datos_post['cmd'] = '_notify-validate';
    
    $opciones = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($datos_post),
            'verify_peer' => true,
            'verify_peer_name' => true,
            'timeout' => 30
        ),
        'ssl' => array(
            'verify_peer' => true,
            'verify_peer_name' => true
        )
    );
    
    try {
        $contexto = stream_context_create($opciones);
        $resultado = file_get_contents($paypal_url, false, $contexto);
        
        return $resultado === 'VERIFIED';
        
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Función para procesar el pago
 */
function procesarPago($datos_post, $conn) {
    try {
        // Validar los datos recibidos
        $idContratacion = filter_var($datos_post['custom'], FILTER_VALIDATE_INT);
        $monto = filter_var($datos_post['mc_gross'], FILTER_VALIDATE_FLOAT);
        $txn_id = filter_var($datos_post['txn_id'], FILTER_SANITIZE_STRING);
        
        if (!$idContratacion || !$monto || !$txn_id) {
            return false;
        }
        
        // Verificar que el pago no haya sido procesado anteriormente
        $queryVerificar = "SELECT idPago FROM pagos WHERE txn_id = :txn_id";
        $stmtVerificar = $conn->prepare($queryVerificar);
        $stmtVerificar->bindParam(':txn_id', $txn_id, PDO::PARAM_STR);
        $stmtVerificar->execute();
        
        if ($stmtVerificar->rowCount() > 0) {
            return false;
        }
        
        // Verificar que la contratación existe y está pendiente de pago
        $queryContratacionCheck = "SELECT idContrato, pago FROM contrataciones 
                                 WHERE idContrato = :idContratacion 
                                 AND estado != 'pagado'";
        $stmtContratacionCheck = $conn->prepare($queryContratacionCheck);
        $stmtContratacionCheck->bindParam(':idContratacion', $idContratacion, PDO::PARAM_INT);
        $stmtContratacionCheck->execute();
        $contratacion = $stmtContratacionCheck->fetch(PDO::FETCH_ASSOC);
        
        if (!$contratacion) {
            return false;
        }
        
        // Verificar que el monto coincide (con un margen de error de 1 centavo)
        if (abs($contratacion['pago'] - $monto) > 0.01) {
            return false;
        }
        
        // Iniciar transacción
        $conn->beginTransaction();
        
        // Registrar el pago
        $queryPago = "INSERT INTO pagos (idContratacion, estado, monto, fechaPago, metodoPago, txn_id) 
                     VALUES (:idContratacion, 'completado', :monto, NOW(), 'paypal', :txn_id)";
        
        $stmtPago = $conn->prepare($queryPago);
        $stmtPago->bindParam(':idContratacion', $idContratacion, PDO::PARAM_INT);
        $stmtPago->bindParam(':monto', $monto, PDO::PARAM_STR);
        $stmtPago->bindParam(':txn_id', $txn_id, PDO::PARAM_STR);
        $stmtPago->execute();
        
        // Actualizar estado de la contratación
        $queryContratacion = "UPDATE contrataciones 
                             SET estado = 'pagado' 
                             WHERE idContrato = :idContratacion";
        
        $stmtContratacion = $conn->prepare($queryContratacion);
        $stmtContratacion->bindParam(':idContratacion', $idContratacion, PDO::PARAM_INT);
        $stmtContratacion->execute();
        
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        if ($conn && $conn->inTransaction()) {
            $conn->rollBack();
        }
        return false;
    }
}

// Recibir y procesar la notificación IPN
$datos_post = $_POST;

// Verificar que sea una notificación válida de PayPal
if (verificarIPN($datos_post)) {
    // Verificar que el pago está completado
    if ($datos_post['payment_status'] == 'Completed') {
        try {
            $connection = new ConnectionDB();
            $conn = $connection->getConnectionDB();
            
            if (procesarPago($datos_post, $conn)) {
                http_response_code(200);
            } else {
                http_response_code(500);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
        }
    } else {
        http_response_code(200); // Aceptamos la notificación pero no procesamos el pago
    }
} else {
    http_response_code(403);
}