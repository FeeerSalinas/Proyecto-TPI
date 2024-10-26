
<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../../index.php");  // Redirigir si no hay sesión activa
    exit();
}

include '../Menu/header.php';   // Header con estilos
include '../Menu/navbarContratista.php';   // Navbar superior
include '../Menu/sidebarContratista.php';  // Sidebar izquierdo

require_once '../../Controladores/FreelancerController.php';
$controller = new FreelancerController();

// Obtener el ID del freelancer de la URL
$idFreelancer = isset($_GET['id']) ? $_GET['id'] : null;
$freelancer = $controller->obtenerFreelancerPorId($idFreelancer);

// Redirigir si no se encuentra el freelancer
if (!$freelancer) {
    echo "<p class='text-center'>El perfil solicitado no existe.</p>";
    exit();
}
?>
<div class="content" id="content">
<h1 class="text-center mt-4">Contratar Freelancer</h1>

<form action="procesarContratacion.php" method="POST">
    <input type="hidden" name="idFreelancer" value="<?= htmlspecialchars($idFreelancer) ?>">
    <input type="hidden" name="idContratista" value="<?= $_SESSION['idUsuario'] ?>">

    <div class="mb-3">
        <label for="titulo" class="form-label">Título del Servicio</label>
        <input type="text" name="titulo" id="titulo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
    </div>

    <div class="mb-3">
        <label for="metodo" class="form-label">Método de Pago</label>
        <select name="metodo" id="metodo" class="form-select">
            <option value="PayPal">PayPal</option>
            <option value="Tarjeta">Tarjeta</option>
            <option value="Transferencia">Transferencia</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
        <input type="date" name="fechaInicio" id="fechaInicio" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="fechaFin" class="form-label">Fecha de Fin</label>
        <input type="date" name="fechaFin" id="fechaFin" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Confirmar Contratación</button>
    <a href="BuscarFreelancer.php" class="btn btn-secondary">Cancelar</a>
</form>
</div>

<?php include '../Menu/footer.php'; ?>




    
