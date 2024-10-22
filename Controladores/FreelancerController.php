<?php
require_once __DIR__ . '/../Modelos/FreelancerModel.php'; // Ajuste de la ruta absoluta

class FreelancerController {
    private $freelancerModel;

    public function __construct() {
        $this->freelancerModel = new FreelancerModel();
    }

    public function buscarFreelancers($nombre = "") {
        return $this->freelancerModel->obtenerFreelancers($nombre);
    }
}
