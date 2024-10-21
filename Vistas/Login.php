<?php
session_start();
if (isset($_SESSION['idUsuario'])) {
    // Si el usuario ya ha iniciado sesión, redirigir a la página correspondiente
    header("Location: " . ($_SESSION['tipoUsuario'] == 'freelancer' ? 'FreelancerHome.php' : 'ContratistaHome.php'));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
    <div class="container">
        <form class="login-form" action="procesar_login.php" method="POST">
            <h3 class="text-center text-white mb-4">FREELAND-CONNECT</h3>
            <?php
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger" role="alert">
                        Usuario o contraseña incorrectos.
                      </div>';
            }
            ?>
            <div class="mb-3">
                <input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
            </div>
            <div class="mb-4">
                <input type="password" class="form-control" name="clave" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-login text-white mb-3">Iniciar sesión</button>
            <div class="text-center mb-3">
                <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-white">No poseo una cuenta</span>
                <a href="Register.php">Registrarse</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>