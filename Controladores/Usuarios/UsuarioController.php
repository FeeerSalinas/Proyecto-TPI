<?php 
    require_once("../../Modelos/UsuarioModel.php");

    class UsuarioController{
        private $ObjUsuarioModel;

        public function __construct()
        {
            $this->ObjUsuarioModel=new UsuarioModel();
        }

        /**
        * Este método inserta un nuevo usuario en la base de datos asignándole un tipo de usuario.
        * @param int $tipoUsuario El tipo de usuario a insertar. 
        *           0 representa "contratista", cualquier otro valor es "Freelancer".
        */
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
    }

    /*
        Asignar los métodos según los REQUEST
    */
    $Tipo = $_REQUEST['tipo'];
    $ObjUsuarioController = new UsuarioController();

    switch($Tipo){
        case "InsertarContratista":
            $ObjUsuarioController->InsertUsuario(0);
            break;
        case "InsertarFreelancer":
            $ObjUsuarioController->InsertUsuario(1);
            break;
    }
?>