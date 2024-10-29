<?php
require_once '../Modelos/ConnectionDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idContrato = $_POST['idContrato'];
    $nuevoEstado = $_POST['estado'];

    try {
        $connection = new ConnectionDB();
        $conn = $connection->getConnectionDB();

        $query = "UPDATE contratacionesf SET estado = ? WHERE idContrato = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$nuevoEstado, $idContrato]);

        header("Location: freelancerContrataciones.php?mensaje=estado_actualizado");
        exit();
    } catch (PDOException $e) {
        echo "<p>Error al actualizar el estado: " . $e->getMessage() . "</p>";
        exit();
    }
}
?>
