<?php
require_once("../Modelos/UsuarioModel.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['usuario'];
    $clave = $_POST['clave'];
    
    $usuarioModel = new UsuarioModel();
    $resultado = $usuarioModel->login($nombreUsuario, md5($clave));
    
    if (!$resultado) {
        // Si el inicio de sesión falla, redirigir de vuelta al formulario con un mensaje de error
        header("Location: login.php?error=1");
        exit();
    }
}
