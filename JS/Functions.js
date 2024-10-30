
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
