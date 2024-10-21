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
            background-color: #00aaff; /* Celeste */
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            background-color: #28a745; /* Verde */
            border-radius: 5px;
        }

        .navbar-brand img {
            height: 40px;
        }

        .vertical-menu {
            width: 250px;
            height: 100vh;
            background-color: #00aaff;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
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
            transition: margin-left 0.3s ease-in-out;
        }

        footer {
            background-color: black;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
        }
    </style>
</head>
<body>
