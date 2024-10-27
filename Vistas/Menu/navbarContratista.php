<nav class="navbar navbar-expand-lg">
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

<!-- Bootstrap JS y Popper.js -->
<script 
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" 
    crossorigin="anonymous">
</script>
<script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" 
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" 
    crossorigin="anonymous">
</script>