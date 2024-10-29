<?php
session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

require_once("../../Modelos/UsuarioModel.php");
include '../Menu/header.php';   
include '../Menu/navbarFreelancer.php';   
include '../Menu/sidebar.php'; 


$usuarioModel = new UsuarioModel();
$perfil = $usuarioModel->obtenerPerfil($_SESSION['idUsuario']);
$categorias = $usuarioModel->obtenerCategorias();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = trim($_POST['descripcion'] ?? '');
    $idCategoria = isset($_POST['categoria']) ? (int)$_POST['categoria'] : null;

    if ($usuarioModel->actualizarPerfilFreelancer($_SESSION['idUsuario'], $descripcion, $idCategoria)) {
        echo "<script>
            Swal.fire({
                title: '¡Éxito!',
                text: 'Perfil actualizado correctamente',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>";
        $perfil = $usuarioModel->obtenerPerfil($_SESSION['idUsuario']);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Freelancer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        .content {
            padding: 3rem;
        }
        .profile-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .profile-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-control, .form-select {
            border-radius: 0.5rem;
        }
        .btn-actualizar {
            background: linear-gradient(135deg, #4F46E5, #4338CA);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-actualizar:hover {
            background: linear-gradient(135deg, #6366F1, #4F46E5);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3);
        }
        textarea {
            resize: none;
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card profile-card p-4">
                        <div class="text-center">
                            <?php if (!empty($perfil['fotoPerfil'])): ?>
                                <img src="<?= htmlspecialchars($perfil['fotoPerfil']) ?>" 
                                     alt="Foto de perfil" class="profile-image">
                            <?php else: ?>
                                <img src="../../IMG/jaker.png" alt="Foto de perfil por defecto" 
                                     class="profile-image">
                            <?php endif; ?>
                        </div>

                        <h2 class="text-center mt-4 mb-4">Mi Perfil</h2>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nombre:</div>
                            <div class="col-md-8"><?= htmlspecialchars($perfil['nombre']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Tipo de Usuario:</div>
                            <div class="col-md-8"><?= htmlspecialchars($perfil['tipoUsuario']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Teléfono:</div>
                            <div class="col-md-8"><?= htmlspecialchars($perfil['telefono']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Dirección:</div>
                            <div class="col-md-8"><?= htmlspecialchars($perfil['direccion']) ?></div>
                        </div>

                        <form method="POST" class="mt-4">
                            <div class="mb-3">
                                <label for="categoria" class="form-label fw-bold">Categoría:</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="">Seleccione una categoría</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= $categoria['idCategoria'] ?>" 
                                            <?= $perfil['idCategoria'] == $categoria['idCategoria'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($categoria['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label fw-bold">Descripción del Perfil:</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" 
                                          placeholder="Describe tu perfil profesional..."><?= 
                                          htmlspecialchars($perfil['descripcionPerfil'] ?? '') ?></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-actualizar">Actualizar Perfil</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include '../Menu/footer.php'; ?>
