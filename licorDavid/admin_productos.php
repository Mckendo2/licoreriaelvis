<?php
session_start();
require_once "config.php";

// Verificar que el usuario es un administrador
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit;
}

// Obtener productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Licorería</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Color pastel de fondo */
        }
        .navbar {
            background-color: #6c757d; /* Color pastel grisáceo */
        }
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .navbar-nav .nav-link:hover {
            color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #4CAF50; /* Color pastel verde */
            color: #ffffff;
        }
        .table tbody tr:nth-child(even) {
            background-color: #e9ecef;
        }
        .table tbody tr:hover {
            background-color: #d4edda; /* Color verde claro al pasar el ratón */
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
        .btn-action {
            background-color: #007bff; /* Color azul para acciones */
            color: #ffffff;
            border-radius: 5px;
            padding: 5px 10px;
            text-decoration: none;
        }
        .btn-action:hover {
            background-color: #0056b3; /* Color azul oscuro */
            color: #ffffff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="admin.php">Inicio Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle pr-4" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION["username"]); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="logout.php">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <h1 class="mt-4">Gestión de Productos</h1>
        <a href="admin_add_product.php" class="btn btn-custom mb-3">Agregar Nuevo Producto</a>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['precio']); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                    <td>
                        <a href="admin_edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-action">Editar</a>
                        <a href="admin_delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-action">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
