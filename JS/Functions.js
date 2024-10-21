function EncriptContrasenia()
{
    var inputContrasenia = document.getElementById("Contrasenia");
    inputContrasenia.value= CryptoJS.MD5(inputContrasenia.value);
}