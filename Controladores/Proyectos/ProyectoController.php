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

        public function getProyectoByIdProyecto(int $idProyecto){

            //Obtener todos los proyectos

            return $this->ObjProyectoModel->getProyectoByIdProyecto($idProyecto);
        }

        public function getProyectosByIdCategoria(int $idCategoria){
            return $this->ObjProyectoModel->getProyectosByIdCategoria($idCategoria);
        }

        public function getAllCategorias(){
            return $this->ObjProyectoModel->getAllCategorias();
        }
    }

?>