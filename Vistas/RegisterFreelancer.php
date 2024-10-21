<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Freeland</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/registrarPerfiles.css">

</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="seccion2 col-md-6  d-flex align-items-center">
                <div class="info-section mx-auto w-75">
                    <h2 class="mb-4">Trabaja como Freelancer y Logra Tus Objetivos</h2>
                    <p>Freeland-connect te ofrece la libertad de trabajar en lo que te apasiona, a tu propio ritmo y desde cualquier lugar.
                        Úneete a una comunidad global de freelancers y encuentra proyectos en categorias que se alineen con tus habilidades y aspiraciones
                        profesionales.
                    </p>
                    <h5 class="mt-4 mb-3">¿Cómo Funciona?</h5>
                    <ol>
                        <li>Crea tu perfil</li>
                        <li>Explora proyectos</li>
                        <li>Envía Propuestas</li>
                        <li>Trabaja y gana</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center">
                <div class="logo mb-4 align-items-start">
                    <img src="../IMG/LogoFreelancer-removebg-preview.png" alt="">
                </div>

                <!-- FORMULARIO QUE SIRVE PARA INSERTAR UN USUARIO -->
                <form class="custom-form w-50" action="../Controladores/Usuarios/UsuarioController.php?tipo=InsertarFreelancer" method="POST">
                    <h3 class="text-center text-white mb-4">CREA UNA CUENTA</h3>

                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Nombre" name="Nombre" id="Nombre">
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Correo" name="Correo" id="Correo">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Usuario" name="Usuario" id="Usuario">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" placeholder="Contraseña" name="Contrasenia" id="Contrasenia">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" placeholder="Confirmar contraseña">
                    </div>
                    <div class="mb-3">
                        <input type="tel" class="form-control" placeholder="Teléfono" name="Telefono" id="Telefono">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Dirección" name="Direccion" id="Direccion">
                    </div>

                    <!-- Encriptar la contra onclick con Javascript"-->
                    <button type="submit" onclick="EncriptContrasenia();" class="btn btn-primary mb-3">Registrarse</button>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white">¿Ya tienes una cuenta?</span>
                        <a href="Login.php">Iniciar sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!--Librería para encriptar la contraseña -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <script src="../JS/Functions.js"></script>
</body>
</html>