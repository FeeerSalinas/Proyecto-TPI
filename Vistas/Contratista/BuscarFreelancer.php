<?php
    include '../Menu/header.php';   // Header con estilos
    include '../Menu/navbarContratista.php';   // Navbar superior
    include '../Menu/sidebarContratista.php';  // Sidebar izquierdo
?>
<?php
require_once '../../Controladores/FreelancerController.php';
$controller = new FreelancerController();
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : "";
$freelancers = $controller->buscarFreelancers($nombre);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Freelancer</title>

    <!-- Bootstrap CSS -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        crossorigin="anonymous"
    />
    
    <!-- Enlace al CSS personalizado -->
    <link rel="stylesheet" href="/Proyecto-TPI/CSS/freelancer.css">
</head>
<body>
<div class="content" id="content">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Buscar Freelancer</h1>

        <!-- Formulario de búsqueda -->
        <form method="GET" class="my-4 d-flex justify-content-center">
            <div class="input-group w-50">
                <input 
                    type="text" 
                    name="nombre" 
                    class="form-control" 
                    placeholder="Buscar freelancers" 
                    value="<?= htmlspecialchars($nombre) ?>"
                >
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>

        <!-- Mostrar resultados -->
        <div class="row">
            <?php if (count($freelancers) > 0): ?>
                <?php foreach ($freelancers as $freelancer): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card profile-card shadow-sm">
                            <div class="card-header d-flex justify-content-center">
                                <img 
                                    src="<?= $freelancer['fotoPerfil'] ?>" 
                                    class="profile-img rounded-circle" 
                                    alt="<?= htmlspecialchars($freelancer['nombreUsuario']) ?>"
                                >
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($freelancer['nombreUsuario']) ?></h5>
                                <p class="card-text text-muted"><?= htmlspecialchars($freelancer['descripcionPerfil']) ?></p>
                                <a href="#" class="btn btn-outline-primary btn-sm">Ver Perfil</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No se encontraron freelancers.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../Menu/footer.php'; ?>
</body>
</html>
