<?php
include '../config.php'; // Ruta ajustada para encontrar config.php

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id <= 0) {
    die("Orden inválida.");
}

// Obtener los detalles de la orden
$sql = "SELECT o.id AS order_id, o.fecha, o.total, u.nombre AS usuario
        FROM ordenes o
        JOIN usuarios u ON o.id_usuario = u.id
        WHERE o.id = :order_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['order_id' => $order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Orden no encontrada.");
}

// Obtener los detalles de los productos en la orden
$sql = "SELECT p.nombre, d.cantidad, d.precio
        FROM detalle_orden d
        JOIN productos p ON d.id_producto = p.id
        WHERE d.id_orden = :order_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['order_id' => $order_id]);
$details = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Orden</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h1>Recibo de Orden</h1>

<p><strong>ID de Orden:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
<p><strong>Fecha:</strong> <?php echo htmlspecialchars($order['fecha']); ?></p>
<p><strong>Usuario:</strong> <?php echo htmlspecialchars($order['usuario']); ?></p>
<p><strong>Total:</strong> <?php echo htmlspecialchars($order['total']); ?></p>

<h2>Detalles de Productos</h2>
<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($details as $detail): ?>
            <tr>
                <td><?php echo htmlspecialchars($detail['nombre']); ?></td>
                <td><?php echo htmlspecialchars($detail['cantidad']); ?></td>
                <td><?php echo htmlspecialchars($detail['precio']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
