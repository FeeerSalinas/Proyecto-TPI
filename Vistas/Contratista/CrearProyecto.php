<?php
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

include '../Menu/header.php';
include '../Menu/navbarContratista.php';
include '../Menu/sidebarContratista.php';

require_once('../../Modelos/ConnectionDB.php');

try {
    $conexion = new ConnectionDB();
    $conn = $conexion->getConnectionDB();
    $query = "SELECT idCategoria, nombre FROM categoria";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "<script>console.log('Error: " . $e->getMessage() . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Proyecto</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="content" id="content">
        <main class="p-6">
            <div class="max-w-3xl mx-auto">
                <!-- Encabezado -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Crear nuevo proyecto
                    </h1>
                    <p class="text-gray-600">Explícale al freelancer tus ideas del proyecto con todos los detalles posibles.</p>
                </div>

                <!-- Formulario -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form id="proyectoForm" action="procesar_proyecto.php" method="POST" class="space-y-6">
                        <!-- Título -->
                        <div class="space-y-2">
                            <label for="titulo" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-heading mr-2"></i>Título del proyecto
                            </label>
                            <input type="text" id="titulo" name="titulo" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Ej: Diseño de logo para empresa de tecnología">
                        </div>

                        <!-- Descripción -->
                        <div class="space-y-2">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-align-left mr-2"></i>Descripción detallada
                            </label>
                            <textarea id="descripcion" name="descripcion" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors h-32"
                                placeholder="Describe tu proyecto con el mayor detalle posible. Incluye objetivos, requisitos y cualquier información relevante."></textarea>
                            <p class="text-sm text-gray-500">Sé específico en tus requerimientos para obtener mejores propuestas.</p>
                        </div>

                        <!-- Categoría -->
                        <div class="space-y-2">
                            <label for="categoria" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-tags mr-2"></i>Categoría del proyecto
                            </label>
                            <select id="categoria" name="categoria" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                                <option value="">Selecciona una categoría</option>
                                <?php
                                if (isset($categorias)) {
                                    foreach($categorias as $categoria) {
                                        echo "<option value='" . htmlspecialchars($categoria['idCategoria']) . "'>" 
                                             . htmlspecialchars($categoria['nombre']) . 
                                             "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Presupuesto -->
                        <div class="space-y-2">
                            <label for="presupuesto" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-dollar-sign mr-2"></i>Presupuesto estimado
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" id="presupuesto" name="presupuesto" required
                                    class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="0.00">
                            </div>
                            <p class="text-sm text-gray-500">Define un presupuesto realista para tu proyecto.</p>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Crear proyecto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="../../JS/errorCrearProyecto.js"></script>
</body>
</html>

<?php include '../Menu/footer.php'; ?>