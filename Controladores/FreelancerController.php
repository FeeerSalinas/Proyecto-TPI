<?php
require_once __DIR__ . '/../Modelos/FreelancerModel.php';

class FreelancerController {
    private $freelancerModel;

    public function __construct() {
        $this->freelancerModel = new FreelancerModel();
    }

    public function obtenerCategorias() {
        return $this->freelancerModel->obtenerCategorias();
    }

    public function buscarFreelancers($nombre, $idCategoria) {
        return $this->freelancerModel->obtenerFreelancers($nombre, $idCategoria);
    }
    public function obtenerFreelancerPorId($id) {
        return $this->freelancerModel->obtenerPorId($id);  
    }
    
}
