<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $id_usuario = $_SESSION['user_id']; // Asegúrate de tener el ID del usuario en la sesión
    $productos = $_POST['productos']; // Esto debe ser un array con los IDs de los productos, cantidades y precios

    // Comenzar la transacción
    $conn->beginTransaction();

    try {
        // Insertar la orden
        $sql = "INSERT INTO ordenes (id_usuario, total) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        // Calcular el total
        $total = array_sum(array_column($productos, 'total'));
        $stmt->execute([$id_usuario, $total]);
        $orden_id = $conn->lastInsertId();

        // Insertar detalles de la orden
        $sql = "INSERT INTO detalle_orden (id_orden, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        foreach ($productos as $producto) {
            $stmt->execute([$orden_id, $producto['id'], $producto['cantidad'], $producto['precio']]);
        }

        // Confirmar la transacción
        $conn->commit();
        echo "Pedido guardado correctamente.";
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        echo "Error al guardar el pedido: " . $e->getMessage();
    }
} else {
    echo "Método de solicitud no permitido.";
}
