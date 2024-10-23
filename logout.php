<?php
session_start(); // Iniciar la sesión

// Limpiar todas las variables de sesión
$_SESSION = [];

// Verificar si las cookies de sesión están habilitadas
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),  // Nombre de la cookie de sesión
        '',              // Valor vacío para eliminar
        time() - 42000,  // Tiempo en el pasado para forzar su eliminación
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruir la sesión completamente
session_destroy();

// Redirigir al usuario al inicio de sesión o pantalla principal
header("Location: index.php");
exit();
?>
