
<head>
    <style>
        /* Asegura que la imagen del navbar tenga tamaño controlado */
        .navbar-brand img {
            height: 40px;   /* Tamaño fijo */
            width: auto;    /* Mantener proporción */
            max-width: 100%; /* Prevenir que se agrande más del contenedor */
        }

        /* Ajustar márgenes para evitar exceso de espacio */
        .navbar-brand {
            padding: 0;
            margin-right: 10px; /* Ajustar espaciado a la derecha */
        }

        /* Estilos para evitar que la imagen se desborde */
        .navbar {
            overflow: hidden;
        }

        /* Imagen de perfil */
        .perfil-img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
        }
    </style>
</head>
<nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
    <div class="container-fluid">
        <!-- Botón de Menú con Estilo Personalizado -->
        <button class="btn btn-menu me-3" id="navbarToggle">☰</button>

        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="../../IMG/logo.png" alt="Logo" class="logo-spacing">
        </a>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../Freelancer/BuscarProyectos.php">Buscar Proyectos</a></li>
                <li class="nav-item"><a class="nav-link" href="../Freelancer/misPropuestas.php">Mis Propuestas</a></li>
                <li class="nav-item"><a class="nav-link" href="perfilFreelancer.php">Mi Perfil</a></li>
            </ul>

            <!-- Avatar -->
             <img 
                src="<?= $_SESSION['profileImageUrl'] ?? 'https://i.ibb.co/fX54vF2/icon.webp'; ?>" 
                alt="Usuario" 
                class="perfil-img"
            >
            <span class="nombre-usuario ms-3 text-dark">
                <?= htmlspecialchars($_SESSION['nombreUsuario']) ?>
            </span>
        </div>
    </div>
</nav>


