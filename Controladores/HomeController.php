<?php

/*
Este archivo se encarga de manejar las rutas del index al home
*/

if (isset($_REQUEST['Categoria']))
{
    if ($_REQUEST['Categoria']==null or $_REQUEST['Categoria']=="")
    {
        header("Location: ../Controladores/HomeController.php?Categoria=Todas");
    }
    else
    {    
        //Redirigir automáticamente al home
        header("Location: ../Vistas/Home.php?Categoria=".$_REQUEST['Categoria']."");
    }   
}
else
{
    header("Location: ../Controladores/HomeController.php?Categoria=Todas");
}
?>