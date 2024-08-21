<?php
session_start();
require_once "config.php";

// Verificar que el usuario es un administrador
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit;
}

$pedido_id = $_GET['id'];

// Obtener detalles del pedido
$sql = "SELECT p.nombre, dp.cantidad, (p.precio * dp.cantidad) AS subtotal
        FROM detalles_pedido dp
        JOIN productos p ON dp.producto_id = p.id
        WHERE dp.pedido_id = ?";
$result = $conn->prepare($sql);
$result->bind_param("i", $pedido_id);
$result->execute();
$details = $result->get_result();
$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido - Licorería</title>
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
        <h1 class="mt-4">Detalles del Pedido</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $details->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                    <td><?php echo number_format($row['subtotal'], 2); ?></td>
                </tr>
                <?php
                $total += $row['subtotal'];
                endwhile;
                ?>
            </tbody>
        </table>
        <h3>Total: $<?php echo number_format($total, 2); ?></h3>
        <button class="btn btn-custom" onclick="window.print()">Imprimir Recibo</button>
        <a href="admin_ver_pedidos.php" class="btn btn-secondary">Volver a Ver Pedidos</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
