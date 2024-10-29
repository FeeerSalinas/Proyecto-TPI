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
                <li class="nav-item"><a class="nav-link" href="../Freelancer/BuscarProyectos.php">Buscar Proyectos</a></li>
                <li class="nav-item"><a class="nav-link" href="../Freelancer/misPropuestas.php">Mis Propuestas</a></li>
                <li class="nav-item"><a class="nav-link" href="perfilFreelancer.php">Mi Perfil</a></li>
            </ul>

           <!-- Avatar con Menú Desplegable -->
      
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
        </div>
    </div>
</nav>
