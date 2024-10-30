<?php
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

include '../Menu/header.php';
include '../Menu/navbarContratista.php';
include '../Menu/sidebarContratista.php';

require_once '../../Controladores/FreelancerController.php';
$controller = new FreelancerController();

$categorias = $controller->obtenerCategorias();
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : "";
$idCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
$freelancers = $controller->buscarFreelancers($nombre, $idCategoria);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Freelancer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --hover-color: #1d4ed8;
            --background-light: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        #content {
            margin-top: 0;
            padding: 5rem;
            min-height: 100vh;
            background-color: var(--background-light);
        }
        
         .profile-button {
            display: inline-block;
            padding: 0.55rem 2rem;
            border-radius: 0.75rem;
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .profile-button:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .search-container {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .search-input {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .search-button {
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background-color: var(--hover-color);
            transform: translateY(-1px);
        }

        .profile-card {
            width: 100%;
            max-width: 350px;
            height: 350px;
            margin: 3px;
            border-radius: 1rem;
            border: none;
            background: white;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .profile-img-container {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 1.5rem auto;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

       

        .categories-sidebar {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            height: auto;
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
        }

        .category-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .category-list {
            list-style: none;
            padding: 0;
        }

        .category-item {
            padding: 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .category-item:hover {
            background-color: var(--background-light);
        }

        .category-link {
            color: #4a5568;
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
        }

        .category-link:hover {
            color: var(--primary-color);
        }

        .contracted-button {
            width: 100%;
            padding: 0.3rem;
            border-radius: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: -0.2rem;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: #4a5568;
            font-size: 1.1rem;
        }
        .profile-img-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 1rem auto 0.5rem; /* Reducido el margin-bottom de 1.5rem a 0.5rem */
        }

        .card-content {
            padding: 0.55rem 2.5rem 3.5rem; /* Reducido el padding-top de 1.5rem a 0.75rem */
            text-align: center;
        }
        

    </style>
</head>

<div class="content" id="content">
    <div class="row">
        <div class="col-10">
            <div class="search-container">
                <h1 class="text-center mb-4 fw-bold">Encuentra tu Freelancer Ideal</h1>
                <form method="GET" class="d-flex justify-content-center">
                    <div class="input-group w-75">
                        <input 
                            type="text" 
                            name="nombre" 
                            class="form-control" 
                            placeholder="Busca por nombre o habilidades..."
                            value="<?= htmlspecialchars($nombre) ?>"
                        >
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                </form>
            </div>

            <div class="row">
                <?php if (count($freelancers) > 0): ?>
                    <?php foreach ($freelancers as $freelancer): ?>
                        <?php 
                            $fotoPerfil = !empty($freelancer['fotoPerfil']) 
                                ? $freelancer['fotoPerfil'] 
                                : 'https://i.ibb.co/fX54vF2/icon.webp'; 
                        ?>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="profile-card">
                                <div class="profile-img-container">
                                    <img 
                                        src="<?= htmlspecialchars($fotoPerfil) ?>" 
                                        class="profile-img" 
                                        alt="Foto de perfil"
                                    >
                                </div>
                                <div class="card-content">
                                    <h5><?= htmlspecialchars($freelancer['nombre']) ?></h5>
                                    <p><?= htmlspecialchars(substr($freelancer['descripcionPerfil'], 0, 100)) ?>...</p>
                                    <a href="perfilFreelancer.php?id=<?= $freelancer['idUsuario'] ?>" 
                                       class="profile-button">
                                        <i class="fas fa-user-circle me-2"></i>Ver Perfil
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-search mb-3" style="font-size: 3rem; color: #cbd5e0;"></i>
                        <p>No se encontraron freelancers que coincidan con tu búsqueda.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <aside class="col-md-2 position-fixed end-0">
            <div class="categories-sidebar">
                <a href="../Contratista/freelancersContratados.php" class="contracted-button">
                    <i class="fas fa-briefcase"></i>
                    Freelancers Contratados
                </a>

                <h5 class="category-title">Categorías</h5>
                <ul class="category-list">
                    <li class="category-item">
                        <a href="?nombre=<?= $nombre ?>" class="category-link">
                            <i class="fas fa-th-large me-2"></i>Todas
                        </a>
                    </li>
                    <?php foreach ($categorias as $categoria): ?>
                        <li class="category-item">
                            <a href="?nombre=<?= $nombre ?>&categoria=<?= $categoria['idCategoria'] ?>" 
                               class="category-link">
                                <i class="fas fa-tag me-2"></i>
                                <?= htmlspecialchars($categoria['nombre']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../Menu/footer.php'; ?>