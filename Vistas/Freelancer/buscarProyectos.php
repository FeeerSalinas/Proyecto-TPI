<?php
include '../Menu/header.php';   // Header con estilos
include '../Menu/navbar.php';   // Navbar superior
include '../Menu/sidebar.php';  // Sidebar izquierdo

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

$proyectosList = $proyectoController->getAllProyectos();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Proyectos</title>
    <link rel="stylesheet" href="../../CSS/buscarProyectos.css"> <!-- Usamos el CSS para freelancers -->
</head>

<body>
    <div class="content" id="content">
        <main class="p-4">
            <div class="container">
                <h1 class="mb-5">Encuentra proyectos</h1>

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
</body>

</html>

<?php include '../Menu/footer.php'; ?>