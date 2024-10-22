<?php
require_once('../../Modelos/ConnectionDB.php'); 

// Asegurarse de que no haya salida antes del header
ob_start();

header('Content-Type: application/json');

// Iniciar o reanudar la sesión
session_start();

// Verificar si el usuario está logueado y es un contratista
if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'contratista') {
    ob_end_clean();
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'No tiene permisos para crear proyectos. Debe iniciar sesión como contratista.'
    ]);
    exit;
}

try {
    // Crear instancia de conexión
    $conexion = new ConnectionDB();
    $conn = $conexion->getConnectionDB();
    
    // Validar que todos los campos necesarios estén presentes
    if (empty($_POST['titulo']) || empty($_POST['descripcion']) || 
        empty($_POST['categoria']) || empty($_POST['presupuesto'])) {
        throw new Exception('Todos los campos son obligatorios');
    }
    
    // Obtener los datos del formulario
    $idContratista = $_SESSION['idUsuario'];
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $idCategoria = (int)$_POST['categoria'];
    $presupuesto = (float)$_POST['presupuesto'];
    $fechaActual = date('Y-m-d');
    $estado = 'En proceso';
    
    // Preparar la consulta SQL
    $query = "INSERT INTO proyectos (idContratista, titulo, descripcion, idCategoria, presupuesto, fechaPublicación, estado) 
              VALUES (:idContratista, :titulo, :descripcion, :idCategoria, :presupuesto, :fechaPublicacion, :estado)";
    
    $stmt = $conn->prepare($query);
    
    // Vincular parámetros
    $stmt->bindParam(':idContratista', $idContratista);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':idCategoria', $idCategoria);
    $stmt->bindParam(':presupuesto', $presupuesto);
    $stmt->bindParam(':fechaPublicacion', $fechaActual);
    $stmt->bindParam(':estado', $estado);
    
    // Ejecutar la consulta
    $result = $stmt->execute();
    
    if($result) {
        ob_end_clean();
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => '¡Proyecto guardado exitosamente!'
        ]);
    } else {
        throw new Exception('No se pudo guardar el proyecto');
    }
    
} catch(Exception $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al guardar el proyecto: ' . $e->getMessage()
    ]);
}
?>