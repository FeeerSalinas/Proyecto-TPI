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
        public function login($nombreUsuario, $clave) {
            // Primero, obtenemos el usuario por nombre de usuario
            $sql = "SELECT * FROM usuarios WHERE nombreUsuario = ?";
            $query = $this->connectionDB->prepare($sql);
            $query->execute([$nombreUsuario]);
            
            $usuario = $query->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                // Verificamos si la contraseña encriptada coincide
                if ($usuario['clave'] === md5($clave)) {
                    // Inicio de sesión exitoso
                    session_start();
                    $_SESSION['idUsuario'] = $usuario['idUsuario'];
                    $_SESSION['nombreUsuario'] = $usuario['nombreUsuario'];
                    $_SESSION['tipoUsuario'] = $usuario['tipoUsuario'];
                    
                    // Redirigir según el tipo de usuario
                    if ($usuario['tipoUsuario'] == 'freelancer') {
                        header("Location: Freelancer/FreelancerHome.php");
                    } else {
                        header("Location: Contratista/ContratistaHome.php");
                    }
                    exit();
                }
            }
            
            // Si llegamos aquí, el inicio de sesión ha fallado
            return false;
        }

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
        public function InsertUsuario(int $tipoUsuario){

            //Traer los elementos por ID del form
            $nombre = $_REQUEST['Nombre'];
            $correo = $_REQUEST['Correo'];
            $usuario = $_REQUEST['Usuario'];
            $contrasenia = $_REQUEST['Contrasenia'];
            $telefono = $_REQUEST['Telefono'];
            $direccion = $_REQUEST['Direccion'];

            //Insertar un nuevo usuario
            $insert = $this->ObjUsuarioModel->insertUsuario(
                $nombre, $correo, $usuario, $contrasenia, $telefono, $direccion, $tipoUsuario
            );

            if($insert != null){

                if($tipoUsuario == 0){
                    header('Location: ../../Vistas/Contratista/ContratistaHome.php');
                }else{
                    header('Location: ../../Vistas/Freelancer/FreelancerHome.php');   
                }

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