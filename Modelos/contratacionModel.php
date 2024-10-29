<?php
require_once 'ConnectionDB.php';

class ContratacionModel {
    private $db;

    public function __construct() {
        $this->db = (new ConnectionDB())->getConnectionDB();
    }

    public function crearServicio($idFreelancer, $titulo, $descripcion, $idCategoria) {
        $sql = "INSERT INTO servicios (idFreelancer, titulo, descripcion, idCategoria, estado) 
                VALUES (?, ?, ?, ?, 'Activo')";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([$idFreelancer, $titulo, $descripcion, $idCategoria])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function crearContratacion($idServicio, $idFreelancer, $idContratista, $metodo, $fechaInicio, $fechaFin, $pago) {
        $sql = "INSERT INTO contratacionesf (idServicio, idFreelancer, idContratista, metodo, fechaInicio, fechaFin, estado, pago) 
                VALUES (?, ?, ?, ?, ?, ?, 'Pendiente', ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$idServicio, $idFreelancer, $idContratista, $metodo, $fechaInicio, $fechaFin, $pago]);
    }
}
?>
