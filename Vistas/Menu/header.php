<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Freelancer</title>

    <!-- Bootstrap CSS -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
        crossorigin="anonymous"
    />

    <style>
    html, body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    .navbar {
        background-color: #00aaff; /*color celeste*/
        height: 60px; /* Altura del navbar */
    }

    .vertical-menu {
        width: 250px;
        background-color: #00aaff;
        position: fixed;
        top: 60px; /* Comienza debajo del navbar */
        left: 0;
        height: calc(100vh - 60px); /* Altura ajustada al espacio restante */
        overflow-y: auto; /* Desplazamiento si el contenido es largo */
        transition: margin-left 0.3s ease-in-out;
    }

    .vertical-menu a {
        color: white;
        padding: 15px;
        text-decoration: none;
        display: block;
    }

    .vertical-menu a:hover {
        background-color: #28a745;
        color: black;
    }

    .content {
        flex: 1;
        margin-left: 250px;
        padding: 20px;
        margin-top: 60px; /* Ajusta el contenido después del navbar */
        transition: margin-left 0.3s ease-in-out;
    }

    footer {
        background-color: black;
        color: white;
        text-align: center;
        padding: 10px;
        width: 100%;
    }
    .btn-menu {
        background-color: #00aaff; /* Fondo del botón */
        color: white; /* Color del texto */
        border: 2px solid #00aaff; /* Borde del mismo color */
        border-radius: 8px; /* Esquinas redondeadas */
        padding: 10px 20px;
        transition: background-color 0.3s, border-color 0.3s;
    }

    .btn-menu:hover {
        background-color: white; /* Fondo blanco al pasar el mouse */
        color: #00aaff; /* Texto azul */
        border-color: #00aaff; /* Borde azul */
    }
</style>

</head>
<body>
