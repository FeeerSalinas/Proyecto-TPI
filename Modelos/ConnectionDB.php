<?php
    class ConnectionDB{
        //Atributos de conexión
        private $host = "127.0.0.1:3306"; //Cambiar el puerto por localhost
        private $user = "root";
        private $pass = "";
        private $db = "freelanddb";
        private $conDB;
        private $response = "Conexión exitosa";

        public function __construct()
        {
            $cadenaConexion="mysql:host=".$this->host.";dbname=".$this->db.";charset=utf8";

            try{
                $this->conDB=new PDO($cadenaConexion,$this->user,$this->pass);
                $this->conDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                echo "<script>console.log(".$this->response.");</script>";
            }catch(Exception $e){
                //$this->conDB="Error de conexión";
                echo "<script>console.log(".$e->getMessage().");</script>";
            }
        }

        public function getConnectionDB(){
            return $this->conDB;
        }
    }
?>