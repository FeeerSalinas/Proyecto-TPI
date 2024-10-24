<?php
require_once('../../Modelos/ConnectionDB.php');
session_start();

// Verificar si el usuario está autenticado y es un freelancer
if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'freelancer') {
    header("Location: ../login.php");
    exit();
}

$idFreelancer = $_SESSION['idUsuario'];
$proyecto = $_POST['proyecto'];
$descripcion = $_POST['descripcion'];
$monto = $_POST['monto'];
$fechaEnvio = date('Y-m-d');

$conexion = new ConnectionDB();
$conn = $conexion->getConnectionDB();

try {
    // Insertar la propuesta en la base de datos
    $query = "INSERT INTO propuesta (idProyecto, idFreelancer, descripcion, estado, fechaEnvio, montoPropuesto)
              VALUES (:idProyecto, :idFreelancer, :descripcion, 'pendiente', :fechaEnvio, :monto)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idProyecto', $proyecto, PDO::PARAM_INT);
    $stmt->bindParam(':idFreelancer', $idFreelancer, PDO::PARAM_INT);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':fechaEnvio', $fechaEnvio, PDO::PARAM_STR);
    $stmt->bindParam(':monto', $monto, PDO::PARAM_STR);
    $stmt->execute();

    // Redirigir con éxito
    header("Location: misPropuestas.php");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
