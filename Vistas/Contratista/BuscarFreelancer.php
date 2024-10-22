<?php
    include '../Menu/header.php';  
    include '../Menu/navbarContratista.php';  
    include '../Menu/sidebarContratista.php';  

    require_once '../../Controladores/FreelancerController.php';
    $controller = new FreelancerController();

    // Obtener las categorías y parámetros de búsqueda
    $categorias = $controller->obtenerCategorias();
    $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : "";
    $idCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
    $freelancers = $controller->buscarFreelancers($nombre, $idCategoria);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Freelancer</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        crossorigin="anonymous"
    />
    <link rel="stylesheet" href="/Proyecto-TPI/CSS/freelancer.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Contenido principal -->
        <div class="col-md-9 p-4">
            <h1 class="text-center mb-4">Buscar Freelancer</h1>

            <!-- Formulario de búsqueda -->
            <form method="GET" class="my-4 d-flex justify-content-center">
                <div class="input-group w-75">
                    <input 
                        type="text" 
                        name="nombre" 
                        class="form-control" 
                        placeholder="Buscar Freelancer" 
                        value="<?= htmlspecialchars($nombre) ?>"
                    >
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>

            <!-- Mostrar resultados -->
            <div class="row">
                <?php if (count($freelancers) > 0): ?>
                    <?php foreach ($freelancers as $freelancer): ?>
                        <?php 
                            $fotoPerfil = !empty($freelancer['fotoPerfil']) 
                                ? $freelancer['fotoPerfil'] 
                                : '/Proyecto-TPI/IMG/icon.png';
                        ?>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card profile-card shadow-sm d-flex flex-column align-items-center">
                                <img 
                                    src="<?= $fotoPerfil ?>" 
                                    class="profile-img rounded-circle" 
                                    alt="<?= htmlspecialchars($freelancer['nombre']) ?>"
                                >
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?= htmlspecialchars($freelancer['nombre']) ?></h5>
                                    <p class="card-text text-muted">
                                        <?= htmlspecialchars(substr($freelancer['descripcionPerfil'], 0, 60)) ?>...
                                    </p>
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
        <!-- Barra lateral con categorías al lado derecho -->
        <aside class="col-md-2 p-4 bg-light position-fixed end-0" style="height: 100vh;">
            <h5 class="text-center">Categorías</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item text-center">
                    <a href="?nombre=<?= $nombre ?>">Todas</a>
                </li>
                <?php foreach ($categorias as $categoria): ?>
                    <li class="list-group-item text-center">
                        <a href="?nombre=<?= $nombre ?>&categoria=<?= $categoria['idCategoria'] ?>">
                            <?= htmlspecialchars($categoria['nombre']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
    </div>
</div>

<?php include '../Menu/footer.php'; ?>
</body>
</html>