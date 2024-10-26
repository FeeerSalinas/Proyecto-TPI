<?php
    include '../Menu/header.php';   // Header con estilos
    include '../Menu/navbarContratista.php';   // Navbar superior
    include '../Menu/sidebarContratista.php';  // Sidebar izquierdo
    
    // Incluir la clase de conexión
    require_once('../../Modelos/ConnectionDB.php'); // Ajusta la ruta según tu estructura de archivos
    
    try {
        // Crear instancia de conexión
        $conexion = new ConnectionDB();
        $conn = $conexion->getConnectionDB();
        
        // Preparar y ejecutar la consulta
        $query = "SELECT idCategoria, nombre FROM categoria";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        // Obtener resultados
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch(PDOException $e) {
        echo "<script>console.log('Error: " . $e->getMessage() . "');</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/crearProyecto.css">
 
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
</head>
<body>
<div class="content" id="content">
    <main class="p-4">
        <div class="container">
        <h1>Explícale al freelancer tus ideas del proyecto</h1>
        
        <form id="proyectoForm" action="procesar_proyecto.php" method="POST">
            <div class="form-group">
                <label for="titulo">Título del proyecto</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>

            <div class="form-group">
                <label for="categoria">Tipo de categoría</label>
                <select id="categoria" name="categoria" required>
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

            <div class="form-group">
                <label for="presupuesto">Presupuesto</label>
                <input type="number" id="presupuesto" name="presupuesto" required>
            </div>

            <button class="guardar" type="submit">Guardar proyecto</button>
        </form>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../JS/errorCrearProyecto.js"></script>
</body>
</html>

<?php
    include '../Menu/footer.php';
?>