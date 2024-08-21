<?php
session_start();
include '../config.php'; // Asegúrate de que la ruta sea correcta

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: /login.php");
    exit();
}

// Obtener el ID del producto a editar
$id = $_GET['id'];

// Obtener la información actual del producto
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, cantidad = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nombre, $descripcion, $precio, $cantidad, $id]);

    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>
    <form method="post">
        <label for="nombre">Nombre del Producto:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" required><?php echo $producto['descripcion']; ?></textarea><br>

        <label for="precio">Precio:</label><br>
        <input type="number" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required><br>

        <label for="cantidad">Cantidad:</label><br>
        <input type="number" id="cantidad" name="cantidad" value="<?php echo $producto['cantidad']; ?>" required><br>

        <input type="submit" value="Guardar Cambios">
    </form>
    <br>
    <a href="manage_products.php">Volver a la Gestión de Productos</a>
</body>
</html>
