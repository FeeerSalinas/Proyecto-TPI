<?php

include '../Menu/header.php';   // Header con estilos
include '../Menu/sidebarFreelancer.php';   // Navbar superior
include '../Menu/navbarFreelancer.php';  // Sidebar izquierdo

    require_once("../../Modelos/UsuarioModel.php");


    // Iniciar sesión y verificar usuario
    session_start();
    if (!isset($_SESSION['idUsuario'])) {
        header("Location: ../login.php");
        exit();
    }

    // Obtener datos del perfil y categorías
    $usuarioModel = new UsuarioModel();
    $perfil = $usuarioModel->obtenerPerfil($_SESSION['idUsuario']);
    $categorias = $usuarioModel->obtenerCategorias();

    // Procesar actualización de perfil
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $descripcion = trim($_POST['descripcion'] ?? '');
        $idCategoria = isset($_POST['categoria']) ? (int)$_POST['categoria'] : null;

        if ($usuarioModel->actualizarPerfilFreelancer($_SESSION['idUsuario'], $descripcion, $idCategoria)) {
            echo "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Perfil actualizado correctamente',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>";
            $perfil = $usuarioModel->obtenerPerfil($_SESSION['idUsuario']);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil-Freelancer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/perfiles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <div class="content">
        <h1>Hola, <?php echo $perfil['nombre'] ?>!</h1>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include '../Menu/footer.php'; ?>