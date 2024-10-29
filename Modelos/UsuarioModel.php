<?php
require_once("ConnectionDB.php");

class UsuarioModel extends ConnectionDB
{

    // Variables de la entidad Usuario
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

    // Variable para conexión
    private $connectionDB;

    // Constructor por defecto
    public function __construct()
    {
        $this->connectionDB = new ConnectionDB();
        $this->connectionDB = $this->connectionDB->getConnectionDB();
    }

    public function login($nombreUsuario, $clave)
    {
        $sql = "SELECT * FROM usuarios WHERE nombreUsuario = ?";
        $query = $this->connectionDB->prepare($sql);
        $query->execute([$nombreUsuario]);
        $usuario = $query->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $usuario['clave'] === md5($clave)) {
            session_start();
            $_SESSION['idUsuario'] = $usuario['idUsuario'];
            $_SESSION['nombreUsuario'] = $usuario['nombreUsuario'];
            $_SESSION['tipoUsuario'] = $usuario['tipoUsuario'];

            $redirect = ($usuario['tipoUsuario'] === 'freelancer')
                ? "Freelancer/FreelancerHome.php"
                : "Contratista/ContratistaHome.php";
            header("Location: $redirect");
            exit();
        }

        return false;
    }

    public function obtenerPerfil($idUsuario)
    {
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

    public function actualizarDescripcion($idUsuario, $descripcion)
    {
        $sql = "UPDATE usuarios SET descripcionPerfil = ? WHERE idUsuario = ?";
        $query = $this->connectionDB->prepare($sql);
        return $query->execute([$descripcion, $idUsuario]);
    }

    public function obtenerCategorias()
    {
        $sql = "SELECT idCategoria, nombre FROM categoria ORDER BY nombre";
        $query = $this->connectionDB->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarCategoria($idUsuario, $idCategoria)
    {
        $sql = "UPDATE usuarios SET idCategoria = ? WHERE idUsuario = ?";
        $query = $this->connectionDB->prepare($sql);
        return $query->execute([$idCategoria, $idUsuario]);
    }

    public function insertUsuario(string $nombre, string $correo, string $usuario, string $contrasenia, string $telefono, string $direccion, int $tipoUsuario)
    {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->nombreUsuario = $usuario;
        $this->clave = md5($contrasenia); // Encriptación de la contraseña
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->designRole($tipoUsuario);

        $sql = "INSERT INTO usuarios (nombre, correo, clave, tipoUsuario, telefono, direccion, nombreUsuario)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $insert = $this->connectionDB->prepare($sql);
        $insert->execute([
            $this->nombre,
            $this->correo,
            $this->clave,
            $this->tipoUsuario,
            $this->telefono,
            $this->direccion,
            $this->nombreUsuario
        ]);

        return $this->connectionDB->lastInsertId();
    }

    /** 
     * Esta función verifica que no haya otro usuario registrado ya en la base de datos.
     * @return int 1 si existe, false si no existe.
     */
    public function nombreUsuarioAlreadyExists(string $nombreUsuario)
    {
        $sql = "SELECT 1 FROM usuarios WHERE nombreUsuario = ?";
        $queryParameters = array($nombreUsuario);
        $query = $this->connectionDB->prepare($sql);
        $query->execute($queryParameters);
        //Convertir array asociativo, si no hay nada retorna false
        $response = $query->fetch(PDO::FETCH_ASSOC);
        return $response;
    }

    public function designRole(int $tipoUsuario)
    {
        $this->tipoUsuario = ($tipoUsuario === 1) ? "freelancer" : "contratista";
    }


    public function actualizarPerfilFreelancer($idUsuario, $descripcion, $idCategoria)
    {
        try {
            $this->connectionDB->beginTransaction();

            $sqlDesc = "UPDATE usuarios SET descripcionPerfil = ? WHERE idUsuario = ?";
            $queryDesc = $this->connectionDB->prepare($sqlDesc);
            $queryDesc->execute([$descripcion, $idUsuario]);

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
