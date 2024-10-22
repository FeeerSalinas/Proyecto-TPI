<?php
require_once 'ConnectionDB.php';

class FreelancerModel {
    private $db;

    public function __construct() {
        $this->db = (new ConnectionDB())->getConnectionDB();
    }

    public function obtenerFreelancers($nombre) {
        $sql = "SELECT idUsuario, nombreUsuario, descripcionPerfil, fotoPerfil 
                FROM usuarios 
                WHERE tipoUsuario = 'Freelancer' AND nombreUsuario LIKE :nombre";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['nombre' => "%$nombre%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
