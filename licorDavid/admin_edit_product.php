<?php
session_start();
require_once "config.php";

// Verificar que el usuario es un administrador
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit;
}

$id = $_GET['id'];

// Obtener datos del producto
$sql = "SELECT * FROM productos WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $precio = trim($_POST["precio"]);
    $descripcion = trim($_POST["descripcion"]);

    $sql = "UPDATE productos SET nombre = ?, precio = ?, descripcion = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sdsi", $nombre, $precio, $descripcion, $id);
        
        if ($stmt->execute()) {
            header("location: admin_productos.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error al actualizar el producto.</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Licorería</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Color pastel de fondo */
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
        }
        .btn-custom:hover {
            background-color: #45a049; /* Color verde oscuro */
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Editar Producto</h1>
        <form action="admin_edit_product.php?id=<?php echo $id; ?>" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($product['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" class="form-control" step="0.01" value="<?php echo htmlspecialchars($product['precio']); ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($product['descripcion']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-custom">Actualizar Producto</button>
            <a href="admin_productos.php" class="btn btn-secondary">Volver a la Gestión de Productos</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
