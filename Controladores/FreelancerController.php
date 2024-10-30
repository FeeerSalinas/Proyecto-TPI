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
    public function crearServicio($idFreelancer, $titulo, $descripcion) {
        $sql = "INSERT INTO servicios (idFreelancer, titulo, descripcion, estado) VALUES (?, ?, ?, 'disponible')";
        $stmt = $this->freelancerModel->getConnectionDB()->prepare($sql);
        $stmt->execute([$idFreelancer, $titulo, $descripcion]);
        return $this->freelancerModel->getConnectionDB()->lastInsertId();
    }
    
    public function crearContratacion($idProyecto, $idServicio, $idContratista, $idFreelancer, $metodo, $fechaInicio, $fechaFin) {
        $sql = "INSERT INTO contrataciones (idProyecto, idServicio, idContratista, idFreelancer, metodo, estado, fechaInicio, fechaFin, fechaContratacion) 
                VALUES (?, ?, ?, ?, ?, 'en progreso', ?, ?, CURRENT_DATE)";
        $stmt = $this->freelancerModel->getConnectionDB()->prepare($sql);
        $stmt->execute([$idProyecto, $idServicio, $idContratista, $idFreelancer, $metodo, $fechaInicio, $fechaFin]);
    }
    
    
}
