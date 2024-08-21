<?php
session_start();
require_once "config.php";

// Verificar que el usuario es un administrador
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Licorería</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Color pastel de fondo */
        }
        .navbar {
            background-color: #6c757d; /* Color pastel grisáceo */
        }
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .navbar-nav .nav-link:hover {
            color: #f8f9fa;
        }
        .navbar-nav .dropdown-menu {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
        }
        .navbar-nav .dropdown-item {
            color: #333;
        }
        .navbar-nav .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .btn-custom {
            background-color: #4CAF50; /* Color pastel verde */
            color: #ffffff;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-custom:hover {
            background-color: #45a049; /* Color verde oscuro */
            color: #ffffff;
        }
        .nav-item.dropdown .dropdown-menu {
            margin-left: -50px; /* Ajuste para mantener el menú en la pantalla */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="admin.php">Inicio Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle pr-4" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION["username"]); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="logout.php">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <h1 class="mt-4">Panel de Administración</h1>
        <a href="admin_productos.php" class="btn btn-custom">Gestión de Productos</a>
        <a href="admin_pedidos.php" class="btn btn-custom">Registrar Pedido</a>
        <a href="admin_ver_pedidos.php" class="btn btn-custom">Ver Pedidos</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
