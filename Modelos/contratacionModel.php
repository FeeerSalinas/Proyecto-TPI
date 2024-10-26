<?php
require_once 'ConnectionDB.php';

class ContratacionModel {
    private $db;

    public function __construct() {
        $this->db = (new ConnectionDB())->getConnectionDB();
    }

    public function crearContratacion($idFreelancer, $idContratista, $titulo, $descripcion, $metodo, $fechaInicio, $fechaFin) {
        $sql = "INSERT INTO contrataciones (idFreelancer, idContratista, titulo, descripcion, metodo, fechaInicio, fechaFin, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente')";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idFreelancer, $idContratista, $titulo, $descripcion, $metodo, $fechaInicio, $fechaFin]);
    }
}
?>
