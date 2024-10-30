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
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center">
                <div class="logo mb-4 align-items-start">
                    <img src="../IMG/LogoFreelancer-removebg-preview.png" alt="">
                </div>

                <!-- FORMULARIO QUE SIRVE PARA INSERTAR UN USUARIO -->
                <form class="custom-form w-50" action="../Controladores/Usuarios/UsuarioController.php?tipo=InsertarContratista" method="POST" onsubmit="EncriptContrasenia();">
                    <h3 class="text-center text-white mb-4">CREA UNA CUENTA</h3>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Nombre" name="Nombre" id="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Correo" name="Correo" id="Correo" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Usuario" name="Usuario" id="Usuario" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" placeholder="Contraseña" name="Contrasenia" id="Contrasenia" required onfocus="validarContrasenias();">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" placeholder="Confirmar contraseña" id="ConfirmarContrasenia" required>
                        <p id="mensajeError" class="font-weight-bold text-sm text-white bg-danger p-1" style="display:none;">La contraseña no coincide.</p>
                    </div>
                    <div class="mb-3">
                        <input type="tel" class="form-control" placeholder="Teléfono" name="Telefono" id="Telefono" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Dirección" name="Direccion" id="Direccion" required>
                    </div>

                    <button type="submit" class="btn btn-primary mb-3">Registrarse</button>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white">¿Ya tienes una cuenta?</span>
                        <a href="Login.php">Iniciar sesión</a>
                    </div>
                </form>

                <?php
                if (isset($_REQUEST['usuarioExiste'])) {
                    echo '<div class="alert alert-danger position-absolute top-0 start-0 m-3" role="alert">
                El nombre de usuario <strong>' . htmlspecialchars($_REQUEST['usuarioExiste']) . '</strong> ya existe.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                }
                ?>
            </div>
            <div class="seccion2 col-md-6  d-flex align-items-center">
                <div class="info-section mx-auto w-75">
                    <h2 class="mb-4">Contrata Freelancers con Facilidad y Confianza</h2>
                    <p>En Freeland-connect, encontrarás una comunidad de freelancers altamente calificados y listos para llevar tus proyectos al siguiente nivel.</p>
                    <h5 class="mt-4 mb-3">¿Cómo Funciona?</h5>
                    <ol>
                        <li>Publica tu Proyecto</li>
                        <li>Encuentra Talento</li>
                        <li>Contrata con Confianza</li>
                        <li>Pago Seguro</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!--Librería para encriptar la  contraseña -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <script src="../JS/Functions.js" type="module"></script>
    <script>
        
const inputConfirmarContrasenia = document.getElementById("ConfirmarContrasenia");
const inputContrasenia = document.getElementById("Contrasenia");
const msjError = document.getElementById("mensajeError");

//Cuando deje de focusear el input
inputContrasenia.addEventListener("blur", validarContrasenias);
inputConfirmarContrasenia.addEventListener("blur", validarContrasenias);

/* 
    Método para validar que la contraseña ingresada sea confirmada
*/
function validarContrasenias(){

    let contraseniaValue = inputContrasenia.value;
    let contraseniaConfirmarValue = inputConfirmarContrasenia.value;

    if(contraseniaValue != contraseniaConfirmarValue){
        msjError.style.display = 'block';
        inputConfirmarContrasenia.value = "";
    }else{
        msjError.style.display = 'none';
    }

    //console.log("Funciona", contraseniaValue + "=" + contraseniaConfirmarValue);
}

/* 
    Método para encriptar una contraseña.
*/
function EncriptContrasenia()
{
    var inputContrasenia = document.getElementById("Contrasenia");
    inputContrasenia.value= CryptoJS.MD5(inputContrasenia.value);
}

    </script>

</body>

</html>
