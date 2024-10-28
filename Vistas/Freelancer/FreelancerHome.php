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
?>

<head>
<style>
    /* Estilo general del contenido */
    #content {
        margin-top: 0;
        padding-top: 20px;
        min-height: 100vh;
        background-color: #f8f9fa;
    }

    /* Estilo para la sección de bienvenida */
    .welcome-section {
        background-image: url('../../IMG/homec.jpg'); /* Imagen de fondo para freelancers */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 20px;
        padding: 60px 20px;
        color: white;
        margin: 20px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .welcome-content {
        background: rgba(0, 0, 0, 0.6);
        padding: 20px;
        border-radius: 15px;
        display: inline-block;
        width: auto;
    }

    .welcome-section h1 {
        font-family: 'Arial', sans-serif;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 15px;
    }

    .welcome-section p {
        font-style: italic;
        font-weight: 100;
        margin-bottom: 10px;
    }

    /* Estilo para las tarjetas */
    .feature-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 15px;
    }

    .feature-card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>
</head>

<div class="content" id="content">
&nparsl; 
    <!-- Encabezado llamativo -->
    <div class="welcome-section">
        <div class="welcome-content">
            <h1 class="display-5 fw-bold">Bienvenido a tu espacio Freelancer</h1>
            <p class="fs-4">Encuentra proyectos y conecta con clientes potenciales</p>
        </div>
    </div>

    <!-- Tarjetas de características principales -->
    <div class="row g-4 mb-4">
        <!-- Buscar Proyectos -->
        <div class="col-md-6">
            <a href="../Freelancer/BuscarProyectos.php" class="text-decoration-none">
                <div class="card feature-card bg-light h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="feature-icon bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-search-dollar fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="card-title">Buscar Proyectos</h3>
                            <p class="card-text text-muted">Explora oportunidades de trabajo disponibles</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Mis Postulaciones -->
        <div class="col-md-6">
            <a href="../Freelancer/misPropuestas.php" class="text-decoration-none">
                <div class="card feature-card bg-light h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="feature-icon bg-success text-white rounded-circle p-3 me-3">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="card-title">Mis Postulaciones</h3>
                            <p class="card-text text-muted">Gestiona tus propuestas enviadas</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Proyectos Activos -->
        <div class="col-md-6">
            <a href="../Freelancer/ProyectosActivos.php" class="text-decoration-none">
                <div class="card feature-card bg-light h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="feature-icon bg-info text-white rounded-circle p-3 me-3">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="card-title">Proyectos Activos</h3>
                            <p class="card-text text-muted">Administra tus proyectos en curso</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Mi Perfil -->
        <div class="col-md-6">
            <a href="../Freelancer/perfilFreelancer.php" class="text-decoration-none">
                <div class="card feature-card bg-light h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="feature-icon bg-warning text-white rounded-circle p-3 me-3">
                            <i class="fas fa-user-circle fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="card-title">Mi Perfil</h3>
                            <p class="card-text text-muted">Actualiza tu portafolio y habilidades</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../Menu/footer.php'; ?>