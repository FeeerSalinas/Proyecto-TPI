<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../../index.php");  // Redirigir si no hay sesión activa
    exit();
}

include '../Menu/header.php';   // Header con estilos
include '../Menu/navbarContratista.php';   // Navbar superior
include '../Menu/sidebarContratista.php';  // Sidebar izquierdo

require_once '../../Controladores/FreelancerController.php';
$controller = new FreelancerController();

// Obtener el ID del freelancer de la URL
$idFreelancer = isset($_GET['id']) ? $_GET['id'] : null;
$freelancer = $controller->obtenerFreelancerPorId($idFreelancer);

// Redirigir si no se encuentra el freelancer
if (!$freelancer) {
    echo "<p class='text-center'>El perfil solicitado no existe.</p>";
    exit();
}
?>
<div class="content" id="content">
    <div class="container py-5">
        <div class="card shadow-lg p-5">
            <div class="row align-items-center">
                <!-- Imagen y nombre del freelancer -->
                <div class="col-md-4 text-center mb-4">
                    <img 
                        src="<?= !empty($freelancer['fotoPerfil']) ? $freelancer['fotoPerfil'] : '/Proyecto-TPI/IMG/icon.png' ?>" 
                        alt="Foto de <?= htmlspecialchars($freelancer['nombre']) ?>" 
                        class="rounded-circle border border-4 border-primary shadow-sm mb-3" 
                        width="150" height="150"
                    >
                    <h2 class="mt-2"><?= htmlspecialchars($freelancer['nombre']) ?></h2>
                    <p class="text-muted fs-5"><?= htmlspecialchars($freelancer['tipoUsuario']) ?></p>
                </div>

                <!-- Información del Freelancer -->
                <div class="col-md-8">
                    <h3 class="text-primary mb-3">Información de Contacto</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Correo: </strong> 
                            <a href="mailto:<?= htmlspecialchars($freelancer['correo']) ?>">
                                <?= htmlspecialchars($freelancer['correo']) ?>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <strong>Teléfono: </strong> <?= htmlspecialchars($freelancer['telefono']) ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Dirección: </strong> <?= htmlspecialchars($freelancer['direccion']) ?>
                        </li>
                    </ul>

                    <div class="mt-4">
                        <h3 class="text-primary">Acerca de</h3>
                        <p class="fs-5 text-muted">
                            <?= nl2br(htmlspecialchars($freelancer['descripcionPerfil'])) ?>
                        </p>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="mailto:<?= htmlspecialchars($freelancer['correo']) ?>" class="btn btn-success me-2 px-4 py-2">
                            <i class="fas fa-envelope"></i> Contactar
                        </a>
                        <a href="BuscarFreelancer.php" class="btn btn-secondary px-4 py-2">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </a>
                        <!-- Botón de Contratar -->
                        <a href="contratarFreelancer.php?id=<?= $freelancer['idUsuario'] ?>" 
                           class="btn btn-primary px-4 py-2">
                            <i class="fas fa-briefcase"></i> Contratar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../Menu/footer.php'; ?>
