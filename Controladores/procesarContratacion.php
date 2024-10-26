<?php
require_once '../Modelos/ContratacionModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idFreelancer = $_POST['idFreelancer'];
    $idContratista = $_POST['idContratista'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $metodo = $_POST['metodo'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    $contratacionModel = new ContratacionModel();
    $resultado = $contratacionModel->crearContratacion(
        $idFreelancer,
        $idContratista,
        $titulo,
        $descripcion,
        $metodo,
        $fechaInicio,
        $fechaFin
    );

    if ($resultado) {
        header("Location: ../Vistas/Contratista/ContratistaHome.php?mensaje=contratacion_exitosa");
    } else {
        echo "Error al procesar la contratación.";
    }
}
?>
