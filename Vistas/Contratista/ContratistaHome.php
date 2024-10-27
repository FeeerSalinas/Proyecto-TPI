<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");  // Redirigir si no hay sesión activa
    exit();
}
?>
<?php
    include '../Menu/header.php';   // Header con estilos
    include '../Menu/navbarContratista.php';   // Navbar superior
    include '../Menu/sidebarContratista.php';  // Sidebar izquierdo
?>

<!-- Contenedor principal -->
<div class="content" id="content">
    <main class="p-4">
        <h1>Esta es la vista del Contratista</h1>
        <p>Aquí puedes gestionar tus proyectos y encontrar freelancers fácilmente.</p>
    </main>
</div>

<?php
    include '../Menu/footer.php';  // Footer con cierre del HTML
?>
