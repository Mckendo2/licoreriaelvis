<?php
session_start();
include '../config.php'; // Asegúrate de que la ruta sea correcta

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: /login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nombre, $descripcion, $precio, $cantidad]);

    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
</head>
<body>
    <h1>Agregar Producto</h1>
    <form method="post">
        <label for="nombre">Nombre del Producto:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" required></textarea><br>

        <label for="precio">Precio:</label><br>
        <input type="number" id="precio" name="precio" required><br>

        <label for="cantidad">Cantidad:</label><br>
        <input type="number" id="cantidad" name="cantidad" required><br>

        <input type="submit" value="Agregar Producto">
    </form>
    <br>
    <a href="manage_products.php">Volver a la Gestión de Productos</a>
</body>
</html>
