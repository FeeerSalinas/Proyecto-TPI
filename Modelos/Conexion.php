<?php

class Conexion{
    private $host = "localhost";
    private $user = "root";
    private $password = "1234";
    private $database = "freelanddb";
    private $conBD;

    public function _construct(){
        $cadenaConexion = "mysql:host=$this->host;dbname=$this->database";
        try{
            $this->conBD = new PDO($cadenaConexion, $this->user, $this->password);
            $this->conBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Conexión exitosa";
        }
        catch(Exception $e){
            $this->conBD="Error de conexion";
            http_response_code(404);
            $json=array();
            $json["Estado"]="Error";
            $json["Mensaje"]=$e->getMessage();
            $json["data"]=[];
            echo json_encode($json);
            exit;
        }
    }
    
    public function getConexion(){
        return $this->conBD;
    }

}

?>