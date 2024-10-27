<?php
    require_once("../../Modelos/UsuarioModel.php");
    include '../Menu/header.php';   // Header con estilos
    include '../Menu/navbarContratista.php';   // Navbar superior
    include '../Menu/sidebarContratista.php';  // Sidebar izquierdo

    // Iniciar sesión y verificar usuario
    session_start();
    if (!isset($_SESSION['idUsuario'])) {
        header("Location: ../login.php");
        exit();
    }

    // Obtener datos del perfil
    $usuarioModel = new UsuarioModel();
    $perfil = $usuarioModel->obtenerPerfil($_SESSION['idUsuario']);

    // Procesar actualización de descripción
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descripcion'])) {
        $descripcion = trim($_POST['descripcion']);
        if ($usuarioModel->actualizarDescripcion($_SESSION['idUsuario'], $descripcion)) {
            echo "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Descripción actualizada correctamente',
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
    <title>Perfil-Contratista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/perfiles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

</head>
<body>
    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card profile-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <?php if (!empty($perfil['fotoPerfil'])): ?>
                                    <img src="<?php echo $perfil['fotoPerfil']; ?>" alt="Foto de perfil" 
                                        class="rounded-circle profile-image mb-3">
                                <?php else: ?>
                                    <img src="../../IMG/jaker.png" alt="Foto de perfil por defecto" 
                                        class="rounded-circle profile-image mb-3">
                                <?php endif; ?>
                            </div>

                            <h2 class="text-center mb-4">Mi Perfil</h2>
                            
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Nombre:</div>
                                <div class="col-md-8"><?php echo htmlspecialchars($perfil['nombre']); ?></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Tipo de Usuario:</div>
                                <div class="col-md-8"><?php echo htmlspecialchars($perfil['tipoUsuario']); ?></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Teléfono:</div>
                                <div class="col-md-8"><?php echo htmlspecialchars($perfil['telefono']); ?></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Dirección:</div>
                                <div class="col-md-8"><?php echo htmlspecialchars($perfil['direccion']); ?></div>
                            </div>

                            <form method="POST" class="mt-4">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label fw-bold">Descripción del Perfil:</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" 
                                            rows="4" placeholder="Describe tu perfil profesional..."><?php 
                                            echo htmlspecialchars($perfil['descripcionPerfil'] ?? ''); ?></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-actualizar">
                                        Actualizar Perfil
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    include '../Menu/footer.php';
?>