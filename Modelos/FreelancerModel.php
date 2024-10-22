<?php
require_once 'ConnectionDB.php';

class FreelancerModel {
    private $db;

    public function __construct() {
        $this->db = (new ConnectionDB())->getConnectionDB();
    }

    public function obtenerCategorias() {
        $sql = "SELECT * FROM categoria";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerFreelancers($nombre = '', $idCategoria = null) {
        $sql = "SELECT idUsuario, nombre, descripcionPerfil, fotoPerfil 
                FROM usuarios 
                WHERE tipoUsuario = 'Freelancer'
                AND (:nombre = '' OR nombre LIKE :nombrePattern)
                AND (:idCategoria IS NULL OR idCategoria = :idCategoria)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'nombrePattern' => "%$nombre%",
            'idCategoria' => $idCategoria
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
