<?php
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../../index.php");
    exit();
}

include '../Menu/header.php';
include '../Menu/navbarContratista.php';
include '../Menu/sidebarContratista.php';

require_once '../../Controladores/FreelancerController.php';

$controller = new FreelancerController();
$idFreelancer = isset($_GET['id']) ? $_GET['id'] : null;
$freelancer = $controller->obtenerFreelancerPorId($idFreelancer);

if (!$freelancer) {
    echo "<p class='text-center'>El perfil solicitado no existe.</p>";
    exit();
}

// Obtener categorías
$categorias = $controller->obtenerCategorias();
?>

<div class="content" id="content">
    <h1 class="text-center mt-4">Contratar Freelancer</h1>

    <form action="../../Controladores/procesarContratacion.php" method="POST">
        <input type="hidden" name="idFreelancer" value="<?= htmlspecialchars($idFreelancer) ?>">
        <input type="hidden" name="idContratista" value="<?= $_SESSION['idUsuario'] ?>">

        <div class="row">
            <div class="col-md-6 mb-3">
                <h3>Información del Servicio</h3>

                <label for="titulo" class="form-label">Título del Servicio</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>

                <label for="descripcion" class="form-label mt-3">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>

                <label for="categoria" class="form-label mt-3">Categoría</label>
                <select name="idCategoria" id="categoria" class="form-select" required>
                    <option value="">Seleccionar categoría</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= htmlspecialchars($categoria['idCategoria']) ?>">
                            <?= htmlspecialchars($categoria['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <h3>Detalles de la Contratación</h3>

                <label for="metodo" class="form-label">Método de Pago</label>
                <select name="metodo" id="metodo" class="form-select" required>
                    <option value="PayPal">PayPal</option>
                </select>

                <label for="fechaInicio" class="form-label mt-3">Fecha de Inicio</label>
                <input type="date" name="fechaInicio" id="fechaInicio" class="form-control" required>

                <label for="fechaFin" class="form-label mt-3">Fecha de Fin</label>
                <input type="date" name="fechaFin" id="fechaFin" class="form-control" required>

                <label for="pago" class="form-label mt-3">Monto del Pago</label>
                <input type="number" name="pago" id="pago" class="form-control" required>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Confirmar Contratación</button>
            <a href="BuscarFreelancer.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include '../Menu/footer.php'; ?>
