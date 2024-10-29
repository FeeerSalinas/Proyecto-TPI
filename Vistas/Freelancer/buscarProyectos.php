<?php
session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

include '../Menu/header.php';
include '../Menu/navbarFreelancer.php';
include '../Menu/sidebarFreelancer.php';


require_once('../../Controladores/Proyectos/ProyectoController.php');

session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'freelancer') {
    header("Location: ../login.php");
    exit();
}

$idFreelancer = $_SESSION['idUsuario'];
$proyectoController = new ProyectoController();

$proyectosList = !isset($_GET['idCategoria']) 
    ? $proyectoController->getAllProyectos() 
    : $proyectoController->getProyectosByIdCategoria($_GET['idCategoria']);

$categoriasList = $proyectoController->getAllCategorias();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Proyectos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
        }

        .content {
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4F46E5, #4338CA);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #6366F1, #4F46E5);
        }

        .input-group {
            max-width: 600px;
            margin: 0 auto 2rem auto;
        }

        .empty-state {
            text-align: center;
            margin-top: 2rem;
        }

        .empty-state h3 {
            color: #6b7280;
        }

        .card-header {
            background-color: #4F46E5;
            color: white;
            font-size: 1.25rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="content" id="content">
        <main>
            <div class="container">
                <h1 class="text-center mb-5">Encuentra proyectos</h1>

                <div class="input-group mb-4">
                    <select class="form-select" id="categoriaSelector" name="categoria">
                        <option value="">Todas</option>
                        <?php foreach ($categoriasList as $categoria): 
                            $selected = $categoria['idCategoria'] == $_GET['idCategoria'] ? 'selected' : ''; ?>
                            <option value="<?= htmlspecialchars($categoria['idCategoria']) ?>" <?= $selected ?>>
                                <?= htmlspecialchars($categoria['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-outline-secondary" onclick="filtrarProyectos()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <?php if (!$proyectosList): ?>
                    <div class="empty-state">
                        <h3>No hay proyectos disponibles en esta categoría</h3>
                        <p class="text-muted">Explora otras categorías o revisa más tarde.</p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($proyectosList as $proyecto): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <h4 class="card-header">
                                        <?= htmlspecialchars($proyecto['titulo']) ?>
                                    </h4>
                                    <div class="card-body">
                                        <p><strong>Categoría:</strong> <?= htmlspecialchars($proyecto['nombreCategoria']) ?></p>
                                        <p><strong>Presupuesto:</strong> $<?= number_format($proyecto['presupuesto'], 2) ?></p>
                                        <p><strong>Contratista:</strong> <?= htmlspecialchars($proyecto['nombreUsuario']) ?></p>
                                        <p><strong>Fecha de publicación:</strong> <?= htmlspecialchars($proyecto['fechaPublicación']) ?></p>
                                        <div class="d-flex justify-content-end">
                                            <a href="./crearPropuestas.php?idProyecto=<?= $proyecto['idProyecto'] ?>" 
                                               class="btn btn-primary">
                                                Generar propuesta
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="../../JS/buscarProyectos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include '../Menu/footer.php'; ?>
