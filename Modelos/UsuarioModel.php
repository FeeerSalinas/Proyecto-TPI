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
    }
?>