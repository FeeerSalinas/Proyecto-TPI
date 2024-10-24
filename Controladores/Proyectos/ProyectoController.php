<?php 
    require_once("../../Modelos/ProyectoModel.php");

    //Declarar arreglo que contendrá todos los proyectos publicados
    $proyectosPublished = [];

    class ProyectoController{
        private $ObjProyectoModel;

        public function __construct()
        {
            $this->ObjProyectoModel=new ProyectoModel();
        }

        public function getAllProyectos(){

            //Obtener todos los proyectos

            return $this->ObjProyectoModel->getAllProyectos();
        }
    }

?>