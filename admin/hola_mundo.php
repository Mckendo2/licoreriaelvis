<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: /login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Hola Mundo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 20px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .logout {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hola , <?php echo $_SESSION['usuario_nombre']; ?> (Administrador)</h1>
        <p>¡Bienvenido al panel de administración!</p>

        <div class="btn-container">
            <a href="manage_products.php" class="btn">Agregar Productos</a>
            <a href="manage_sales.php" class="btn">Gestionar Ventas</a>
            <a href="list_orders.php" class="btn">Ver ventas</a>
        </div>

        <div class="logout">
            <a href="../logout.php" class="btn">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>
