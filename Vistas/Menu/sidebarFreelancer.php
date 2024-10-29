<div id="sidebar" class="vertical-menu">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="FreelancerHome.php">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="BuscarProyectos.php">
                <i class="fas fa-folder-plus"></i> Buscar Proyectos
            </a>
        </li>
        
        <!-- Menú desplegable para Mis Propuestas -->
        <li class="nav-item">
            <a 
                class="nav-link dropdown-toggle" 
                href="#" 
                data-bs-toggle="collapse" 
                data-bs-target="#submenuPropuestas" 
                aria-expanded="false" 
                aria-controls="submenuPropuestas"
            >
                <i class="fas fa-search"></i> Mis Propuestas
            </a>
            <div class="collapse" id="submenuPropuestas">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="MisPropuestas.php">
                            <i class="fas fa-paper-plane"></i> Propuestas Enviadas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Freelancer/freelancerContrataciones.php">
                            <i class="fas fa-handshake"></i> Propuestas de Contratistas
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="perfilFreelancer.php">
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

<!-- Asegúrate de incluir los scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
