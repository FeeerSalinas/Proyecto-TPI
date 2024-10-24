<?php 
    require_once("ConnectionDB.php");

    class ProyectoModel extends ConnectionDB{
        //Variables de la entidad Usuario
        private $idProyecto;
        private $idContratista;
        private $titulo;
        private $descripcion;
        private $idCategoria;
        private $presupuesto;
        private $fechaPublicacion;
        private $estado;

        //Variable para conexión
        private $connectionDB;

        //Constructor por defecto
        public function __construct()
        {
            $this->connectionDB = new ConnectionDB();
            $this->connectionDB = $this->connectionDB->getConnectionDB();
        }

        /**
         * Método para obtener todos los proyectos publicados.
          * @return array Un arreglo asociativo con los datos del proyecto.
         */
        public function getAllProyectos()
        {
            $sql = "SELECT p.idProyecto, p.titulo, p.presupuesto, p.fechaPublicación, c.nombre AS nombreCategoria, u.nombre AS nombreUsuario FROM 
            proyectos AS p
            JOIN categoria AS c ON p.idCategoria = c.idCategoria
            JOIN usuarios AS u ON p.idContratista = u.idUsuario";

            $execute = $this->connectionDB->query($sql);
            $resultado = $execute->fetchall(PDO::FETCH_ASSOC);
            return $resultado;
        }
    }
?>