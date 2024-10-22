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


        //Variable para conexión
        private $connectionDB;

        //Constructor por defecto
        public function __construct()
        {
            $this->connectionDB=new ConnectionDB();
            $this->connectionDB=$this->connectionDB->getConnectionDB();
        }

        /*
            Método para asignar el rol a un usuario
        */
        function designRole(int $tipoUsuario){
            switch ($tipoUsuario) {
                case 1:
                    $this->tipoUsuario = "freelancer";
                    break;
                default:
                    $this->tipoUsuario = "contratista";
                    break;
            }
        }

        /*
            Método para registrar un usuario como "Contratista"
        */
        public function insertUsuario(string $nombre, string $correo, string $usuario, string $contrasenia, string $telefono, string $direccion, int $tipoUsuario){
            $this->nombre = $nombre;
            $this->correo = $correo;
            $this->nombreUsuario = $usuario;
            $this->clave = $contrasenia;
            $this->telefono = $telefono;
            $this->direccion = $direccion;

            //0 --> Contratista, 1 --> Freelancer
            $this->designRole($tipoUsuario);

            $sql="INSERT INTO usuarios (nombre, correo, clave, tipoUsuario, telefono, direccion, nombreUsuario)
            VALUES(?,?,?,?,?,?,?)";

            $insert=$this->connectionDB->prepare($sql);
            $queryParameters = array(
                $this->nombre, $this->correo, $this->clave, $this->tipoUsuario, $this->telefono, $this->direccion, $this->nombreUsuario
            );

            $insertResult = $insert->execute($queryParameters);

            $this->idUsuario=$this->connectionDB->lastInsertId();

            return $this->idUsuario;
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
            $sql = "SELECT nombre, tipoUsuario, telefono, direccion, descripcionPerfil, fotoPerfil 
                    FROM usuarios 
                    WHERE idUsuario = ?";
            $query = $this->connectionDB->prepare($sql);
            $query->execute([$idUsuario]);
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        
        public function actualizarDescripcion($idUsuario, $descripcion) {
            $sql = "UPDATE usuarios SET descripcionPerfil = ? WHERE idUsuario = ?";
            $query = $this->connectionDB->prepare($sql);
            return $query->execute([$descripcion, $idUsuario]);
        }
        

    }
?>