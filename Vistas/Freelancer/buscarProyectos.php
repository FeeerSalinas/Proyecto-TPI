<?php
include '../Menu/header.php';   // Header con estilos
include '../Menu/sidebarFreelancer.php';   // Navbar superior
include '../Menu/navbarFreelancer.php';  // Sidebar izquierdo

require_once('../../Controladores/Proyectos/ProyectoController.php');

session_start();

// Verificar si el usuario está autenticado y es un freelancer
if (!isset($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] !== 'freelancer') {
    header("Location: ../login.php");
    exit();
}

$idFreelancer = $_SESSION['idUsuario'];
$conexion = new ConnectionDB();

$proyectoController = new ProyectoController();

//Lista de los proyectos
$proyectosList = null;

if(!isset($_GET['idCategoria'])){
    $proyectosList = $proyectoController->getAllProyectos();
}else{
    $proyectosList = $proyectoController->getProyectosByIdCategoria($_GET['idCategoria']);
}


$categoriasList = $proyectoController->getAllCategorias();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Proyectos</title>
    <link rel="stylesheet" href="../../CSS/buscarProyectos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<body>
    <div class="content" id="content">
        <main class="p-4">
            <div class="container">
                <h1 class="mb-5">Encuentra proyectos</h1>

                <p class="text-info fs-5 mb-3 me-5 ms-5"><strong>Busque por categoría:</strong></p>

                <div class="input-group mb-3 w-auto me-5 ms-5 mb-5">

                    <select class="form-select" id="categoriaSelector" name="categoria" required>
                        <option value="">Todas</option>

                        <?php
                        
                        if (isset($categoriasList)) {
                            foreach ($categoriasList as $categoria) {
                                $selected = $categoria['idCategoria'] == $_GET['idCategoria'] ? 'selected' : '';

                                echo "<option value='" . htmlspecialchars($categoria['idCategoria']) . "'$selected>"
                                    . htmlspecialchars($categoria['nombre']) .
                                    "</option>";
                            }
                        }
                        ?>
                    </select>
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="filtrarProyectos()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <?php
                if (!$proyectosList) {
                    echo '<p class="text-info fs-5 mb-3 me-5 ms-5"><strong>No hay proyectos publicados con esa categoría.</strong></p>';
                }
                ?>

                <!--Recorrer lista de proyectos -->
                <?php foreach ($proyectosList as $proyecto): ?>
                    <div class="card m-3">
                        <h4 class="card-header" id="proyectoTitulo">Aplicacion web</h4>
                        <div class="card-body p-4">
                            <p class="card-text"><?php echo $proyecto['titulo']; ?></p>
                            <p class="card-text"><strong>Categoría: </strong><?php echo $proyecto['nombreCategoria']; ?></p>
                            <p class="card-text"><strong>Presupuesto: </strong><?php echo $proyecto['presupuesto']; ?></p>
                            <p class="card-text"><strong>Nombre de contratista: </strong><?php echo $proyecto['nombreUsuario']; ?></p>
                            <p class="card-text"><strong>Fecha de publicación: </strong><?php echo $proyecto['fechaPublicación']; ?></p>

                            <!-- Botón enviar propuestas -->
                            <div class="d-flex justify-content-end">
                                <a href="./crearPropuestas.php?idProyecto=<?php echo $proyecto['idProyecto']; ?>" class="btn btn-primary" id="propuesta">Generar propuesta</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </main>
    </div>

    <script src="../../JS/buscarProyectos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include '../Menu/footer.php'; ?>