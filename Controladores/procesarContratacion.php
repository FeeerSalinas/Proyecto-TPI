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
    $pago = $_POST['pago'];

    $contratacionModel = new ContratacionModel();
    $idServicio = $contratacionModel->crearServicio($idFreelancer, $titulo, $descripcion, $_POST['idCategoria']);

    if ($idServicio) {
        $resultado = $contratacionModel->crearContratacion($idServicio, $idFreelancer, $idContratista, $metodo, $fechaInicio, $fechaFin, $pago);
        if ($resultado) {
            header("Location: ../Vistas/Contratista/freelancersContratados.php?mensaje=contratacion_exitosa");
            exit();
        }
    }
    echo "Error al procesar la contratación.";
}
?>
