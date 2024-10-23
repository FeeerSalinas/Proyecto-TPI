<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freeland-Connect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/home.css"/>
</head>
<body>
    <div class="container-fluid vh-100 d-flex flex-column">
            <header class="row py-3">
            <div class="col-12 text-end pe-4">
                
                <p class="mb-2 text-end fs-4">¿Listo para empezar?</p>
                <div class="button-container">
                    <a href="Login.php" class="btn btn-warning">Iniciar sesión</a>
                    <a href="Register.php" class="btn btn-warning ms-2">Registrarse</a>
                </div>
            </div>
            </header>
        
        <main class="row flex-grow-1 align-items-center">
            <div class="col text-center">
                <h1>FREELAND-CONNECT</h1>
                <p class="subtitulo">Donde las habilidades y las oportunidades se encuentran.</p>
                <div>
                    <a href="RegisterFreelancer.php" class="btn btn-custom btn-freelancer">Trabajar como freelancer</a>
                    <a href="RegisterContratista.php" class="btn btn-custom btn-proyecto">Contratar un proyecto</a>
                </div>
            </div>
        </main>

        <footer class="row">
            <div class="col text-center">
                <p class="mb-0">"Impulsa tus proyectos con el talento freelance más capacitado.</p>
                <p class="mb-0">Conecta, colabora y paga de forma sencilla y segura."</p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>