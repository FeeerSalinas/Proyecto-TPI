<?php
require_once __DIR__ . '/../Modelos/FreelancerModel.php'; // Ajuste de la ruta absoluta

class FreelancerController {
    private $freelancerModel;

    public function __construct() {
        $this->freelancerModel = new FreelancerModel();
    }

    public function obtenerCategorias() {
        return $this->freelancerModel->obtenerCategorias();
    }

    public function buscarFreelancers($nombre = "", $idCategoria = null) {
        return $this->freelancerModel->obtenerFreelancers($nombre, $idCategoria);
    }
}
