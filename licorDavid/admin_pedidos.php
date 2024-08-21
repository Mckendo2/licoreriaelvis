<?php
session_start();
require_once "config.php";

// Verificar que el usuario es un administrador
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit;
}

// Procesar el formulario de pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_nombre = trim($_POST["cliente_nombre"]);
    $productos = $_POST["productos"];
    $cantidad = $_POST["cantidad"];
    $efectivo = trim($_POST["efectivo"]);
    $total = 0;

    // Crear un nuevo pedido
    $sql = "INSERT INTO pedidos (cliente_nombre) VALUES (?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $cliente_nombre);
        $stmt->execute();
        $pedido_id = $stmt->insert_id;
        $stmt->close();

        // Insertar detalles del pedido
        foreach ($productos as $index => $producto_id) {
            $cantidad_producto = $cantidad[$index];

            // Obtener el precio del producto
            $sql = "SELECT precio FROM productos WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $producto_id);
                $stmt->execute();
                $stmt->bind_result($precio);
                $stmt->fetch();
                $stmt->close();
                
                // Calcular el total
                $total += $precio * $cantidad_producto;

                // Insertar detalle del pedido
                $sql = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("iii", $pedido_id, $producto_id, $cantidad_producto);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        $cambio = $efectivo - $total;

        echo "<div class='container mt-4'>";
        echo "<h3 class='alert alert-success'>Pedido registrado con éxito. Total: $" . number_format($total, 2) . "</h3>";
        echo "<h3 class='alert alert-info'>Efectivo recibido: $" . number_format($efectivo, 2) . "</h3>";
        echo "<h3 class='alert alert-warning'>Cambio a devolver: $" . number_format($cambio, 2) . "</h3>";
        echo "<a href='admin.php' class='btn btn-primary'>Volver al Panel de Administración</a>";
        echo "</div>";
    }
}

// Obtener productos para el formulario
$sql = "SELECT id, nombre, precio FROM productos";
$result = $conn->query($sql);
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pedido - Licorería</title>
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
    <script>
        function updateTotal() {
            let productos = document.getElementsByName('productos[]');
            let cantidades = document.getElementsByName('cantidad[]');
            let total = 0;

            for (let i = 0; i < productos.length; i++) {
                let precio = productos[i].options[productos[i].selectedIndex].dataset.precio;
                let cantidad = cantidades[i].value;
                if (precio) {
                    total += parseFloat(precio) * cantidad;
                }
            }

            document.getElementById('total').value = total.toFixed(2);
        }

        function validateForm() {
            let total = parseFloat(document.getElementById('total').value);
            let efectivo = parseFloat(document.getElementById('efectivo').value);

            if (isNaN(total) || isNaN(efectivo)) {
                alert('Por favor, ingrese valores válidos.');
                return false;
            }

            if (efectivo < total) {
                alert('El monto en efectivo es menor que el total. Por favor, ingrese un monto suficiente.');
                return false;
            }
            return true;
        }

        function addProduct() {
            var div = document.createElement('div');
            div.classList.add('form-row', 'mb-2');
            div.innerHTML = `
                <div class="col">
                    <select name="productos[]" class="form-control" onchange="updateTotal();" required>
                        <option value="">Seleccionar producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?php echo $producto['id']; ?>" data-precio="<?php echo $producto['precio']; ?>">
                                <?php echo htmlspecialchars($producto['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col">
                    <input type="number" name="cantidad[]" class="form-control" min="1" value="1" onchange="updateTotal();" required>
                </div>
            `;
            document.getElementById('productos').appendChild(div);
            updateTotal();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Registrar Pedido</h1>
        <form action="admin_pedidos.php" method="post" onsubmit="return validateForm();">
            <div class="form-group">
                <label for="cliente_nombre">Nombre del Cliente:</label>
                <input type="text" id="cliente_nombre" name="cliente_nombre" class="form-control" required>
            </div>
            
            <h3>Productos:</h3>
            <div id="productos">
                <div class="form-row mb-2">
                    <div class="col">
                        <select name="productos[]" class="form-control" onchange="updateTotal();" required>
                            <option value="">Seleccionar producto</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?php echo $producto['id']; ?>" data-precio="<?php echo $producto['precio']; ?>">
                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col">
                        <input type="number" name="cantidad[]" class="form-control" min="1" value="1" onchange="updateTotal();" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addProduct()">Agregar otro producto</button><br><br>
            
            <div class="form-group">
                <label for="total">Total:</label>
                <input type="text" id="total" name="total" class="form-control" readonly>
            </div>
            
            <div class="form-group">
                <label for="efectivo">Monto en Efectivo:</label>
                <input type="number" id="efectivo" name="efectivo" class="form-control" step="0.01" min="0" required>
            </div>
            
            <button type="submit" class="btn btn-custom">Registrar Pedido</button>
        </form>
        
        <a href="admin.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
