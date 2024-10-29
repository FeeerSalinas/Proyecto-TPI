<div id="sidebar" class="vertical-menu">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="..\Contratista\ContratistaHome.php">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>

        <!-- Menú desplegable para Freelancers -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#freelancerMenu" role="button" aria-expanded="false" aria-controls="freelancerMenu">
                <i class="fas fa-folder-plus"></i> Buscar Freelancers
            </a>
            <div class="collapse" id="freelancerMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="..\Contratista\BuscarFreelancer.php">
                            <i class="fas fa-search"></i> Todos los Freelancers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="..\Contratista\FreelancersContratados.php">
                            <i class="fas fa-user-check"></i> Freelancers Contratados
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="CrearProyecto.php">
                <i class="fas fa-tasks"></i> Crear Proyecto
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../Contratista/MisProyectos.php">
                <i class="fas fa-search"></i> Mis Proyectos
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../Contratista/perfilContratista.php">
                <i class="fas fa-user"></i> Mi Perfil
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../../logout.php">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </li>
    </ul>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
