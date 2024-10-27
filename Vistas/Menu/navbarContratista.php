
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
    </style>
</head>
<nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
    <div class="container-fluid">
        <!-- Botón de Menú con Estilo Personalizado -->
        <button class="btn btn-menu me-3" id="navbarToggle">☰</button>

        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="../../IMG/logo.png" alt="Logo" height="40" class="logo-spacing">
        </a>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="..\Contratista\BuscarFreelancer.php">Buscar Freelancers</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Crear Proyecto</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Mis proyectos</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Mi Perfil</a></li>
            </ul>

            <!-- Avatar con Menú Desplegable -->
            <div class="dropdown">
                <a 
                    href="#" 
                    class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                    id="dropdownUser" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false"
                >
                    <img 
                        src="../../IMG/user.png" 
                        alt="Usuario" 
                        class="rounded-circle perfil-img" 
                        height="40"
                    >
                    <span class="nombre-usuario ms-3 text-dark">
                        <?= htmlspecialchars($_SESSION['nombreUsuario']) ?>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item" href="#">Mi Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../../logout.php">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
