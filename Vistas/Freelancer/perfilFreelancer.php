<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");  // Redirigir si no hay sesión activa
    exit();
}

require_once("../../Modelos/UsuarioModel.php");
include '../Menu/header.php';   // Header con estilos
include '../Menu/navbarFreelancer.php';   // Navbar superior
include '../Menu/sidebarFreelancer.php';  // Sidebar izquierdo

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

// Definir arrays de avatares según el género
$maleAvatars = [
    "https://i.ibb.co/R7dcGT1/avatar-freelancer-hombre-1.png",
    "https://i.ibb.co/P4X66Gp/avatar-freelancer-hombre-2.png",
    "https://i.ibb.co/k6NDFd6/avatar-freelancer-hombre-3.png"
];

$femaleAvatars = [
    "https://i.ibb.co/KWt0pfL/avatar-freelancer-mujer.png",
    "https://i.ibb.co/GPXCRR0/avatar-freelancer-mujer-2.png",
    "https://i.ibb.co/BBYrPDn/avatar-freelancer-mujer-3.png"
];

// Determinar el género del usuario y asignar avatar una sola vez
if (!isset($_SESSION['profileImageUrl'])) {
    function getGender($name) {
        $url = "https://api.genderize.io?name=" . urlencode($name);
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        return isset($data['gender']) ? $data['gender'] : null;
    }

    $gender = getGender($perfil['nombre']);
    if ($gender === 'male') {
        $randomKey = array_rand($maleAvatars);
        $_SESSION['profileImageUrl'] = $maleAvatars[$randomKey];
    } else {
        $randomKey = array_rand($femaleAvatars);
        $_SESSION['profileImageUrl'] = $femaleAvatars[$randomKey];
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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card profile-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <img src="<?php echo $_SESSION['profileImageUrl']; ?>" alt="Foto de perfil" 
                                     class="rounded-circle profile-image mb-3">
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
