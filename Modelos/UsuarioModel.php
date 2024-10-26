<?php
    require_once("ConnectionDB.php");

    class UsuarioModel extends ConnectionDB{

        //Variables de la entidad Usuario
        private $idUsuario;
        private $nombre;
        private $correo;
        private $nombreUsuario;
        private $clave;
        private $telefono;
        private $direccion;
        private $tipoUsuario;
        private $descripcionPerfil;
        private $fotoPerfil;
        private $idCategoria;

        //Variable para conexión
        private $connectionDB;

        //Constructor por defecto
        public function __construct()
        {
            $this->connectionDB=new ConnectionDB();
            $this->connectionDB=$this->connectionDB->getConnectionDB();
        }

        // ... [Mantener los métodos existentes] ...

        public function obtenerPerfil($idUsuario) {
            $sql = "SELECT u.nombre, u.tipoUsuario, u.telefono, u.direccion, 
                           u.descripcionPerfil, u.fotoPerfil, u.idCategoria,
                           c.nombre as nombreCategoria
                    FROM usuarios u 
                    LEFT JOIN categoria c ON u.idCategoria = c.idCategoria 
                    WHERE u.idUsuario = ?";
            $query = $this->connectionDB->prepare($sql);
            $query->execute([$idUsuario]);
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function actualizarDescripcion($idUsuario, $descripcion) {
            $sql = "UPDATE usuarios SET descripcionPerfil = ? WHERE idUsuario = ?";
            $query = $this->connectionDB->prepare($sql);
            return $query->execute([$descripcion, $idUsuario]);
        }

        // Nuevos métodos para manejar categorías
        public function obtenerCategorias() {
            try {
                $sql = "SELECT idCategoria, nombre FROM categoria ORDER BY nombre";
                $query = $this->connectionDB->prepare($sql);
                $query->execute();
                return $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return [];
            }
        }

        public function actualizarCategoria($idUsuario, $idCategoria) {
            try {
                $sql = "UPDATE usuarios SET idCategoria = ? WHERE idUsuario = ?";
                $query = $this->connectionDB->prepare($sql);
                return $query->execute([$idCategoria, $idUsuario]);
            } catch (PDOException $e) {
                return false;
            }
        }

        // Método combinado para actualizar descripción y categoría
        public function actualizarPerfilFreelancer($idUsuario, $descripcion, $idCategoria) {
            try {
                $this->connectionDB->beginTransaction();

                // Actualizar descripción
                $sqlDesc = "UPDATE usuarios SET descripcionPerfil = ? WHERE idUsuario = ?";
                $queryDesc = $this->connectionDB->prepare($sqlDesc);
                $queryDesc->execute([$descripcion, $idUsuario]);

                // Actualizar categoría
                $sqlCat = "UPDATE usuarios SET idCategoria = ? WHERE idUsuario = ?";
                $queryCat = $this->connectionDB->prepare($sqlCat);
                $queryCat->execute([$idCategoria, $idUsuario]);

                $this->connectionDB->commit();
                return true;
            } catch (PDOException $e) {
                $this->connectionDB->rollBack();
                return false;
            }
        }
    }
?>