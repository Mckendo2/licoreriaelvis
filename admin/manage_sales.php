<?php
session_start();
include '../config.php';

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: /login.php");
    exit();
}

// Obtener la lista de productos
$sql = "SELECT * FROM productos";
$stmt = $conn->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // Obtener el precio del producto
    $sql = "SELECT precio FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$producto_id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        $total = $producto['precio'] * $cantidad;

        // Insertar la venta en la base de datos
        $sql = "INSERT INTO ventas (producto_id, cantidad, total) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$producto_id, $cantidad, $total]);

        $mensaje = "Venta realizada con éxito. Total: $" . number_format($total, 2);
        $mensaje_clase = "is-success";
    } else {
        $mensaje = "Producto no encontrado.";
        $mensaje_clase = "is-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Venta</title>
    <!-- Incluye el CSS de Bulma -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css">
</head>
<body>
    <div class="container">
        <h1 class="title is-3">Realizar Venta</h1>

        <!-- Mensaje de éxito o error -->
        <?php if (isset($mensaje)): ?>
            <div class="notification <?php echo $mensaje_clase; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="box">
            <div class="field">
                <label for="producto_id" class="label">Producto:</label>
                <div class="control">
                    <div class="select">
                        <select id="producto_id" name="producto_id" required>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?php echo $producto['id']; ?>"><?php echo $producto['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <label for="cantidad" class="label">Cantidad:</label>
                <div class="control">
                    <input type="number" id="cantidad" name="cantidad" class="input" required>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input type="submit" value="Realizar Venta" class="button is-link">
                </div>
            </div>
        </form>

        <br>
        <a class="button is-info" href="hola_mundo.php">Volver al Dashboard</a>
    </div>
</body>
</html>
