<?php
session_start();
require_once "config.php";

// Verificar que el usuario es un administrador
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit;
}

$id = $_GET['id'];

// Verificar si se ha confirmado la eliminación
if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    $sql = "DELETE FROM productos WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            header("location: admin_productos.php");
            exit;
        } else {
            echo "Error al eliminar el producto.";
        }
        $stmt->close();
    }
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Eliminación - Licorería</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Confirmar Eliminación</h1>
        <p>¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
        <form action="admin_delete_product.php?id=<?php echo $id; ?>" method="post">
            <button type="submit" name="confirm" value="yes" class="btn btn-danger">Eliminar</button>
            <a href="admin_productos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
