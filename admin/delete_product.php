<?php
session_start();
include '../config.php'; // Asegúrate de que la ruta sea correcta

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: /login.php");
    exit();
}

// Obtener el ID del producto a eliminar
$id = $_GET['id'];

// Preparar y ejecutar la consulta para eliminar el producto
$sql = "DELETE FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

// Redirigir de vuelta a la página de gestión de productos
header("Location: manage_products.php");
exit();
?>
