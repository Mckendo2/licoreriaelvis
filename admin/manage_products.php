<?php
session_start();
include '../config.php'; // Asegúrate de que la ruta sea correcta

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: /login.php");
    exit();
}

// Obtener todos los productos de la base de datos
$sql = "SELECT * FROM productos";
$stmt = $conn->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos</title>
    <!-- Incluye el CSS de Bulma -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .table-container {
            margin-bottom: 20px;
        }
        .action-buttons a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title is-3">Gestionar Productos</h1>

        <a href="add_product.php" class="button is-primary">Agregar Producto</a>

        <div class="table-container">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['id']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                            <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                            <td class="action-buttons">
                                <a href="edit_product.php?id=<?php echo htmlspecialchars($producto['id']); ?>" class="button is-info is-small">Editar</a>
                                <a href="delete_product.php?id=<?php echo htmlspecialchars($producto['id']); ?>" class="button is-danger is-small" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
