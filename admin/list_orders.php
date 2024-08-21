<?php
include '../config.php'; // Incluye la conexión a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Ventas</title>
    <!-- Incluye el CSS de Bulma -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css">
    <style>
        .table-container {
            margin: 20px;
        }
        .total-amount {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-container">
            <?php
            // Verifica si se ha seleccionado una venta
            if (isset($_GET['venta_id'])) {
                $venta_id = $_GET['venta_id'];

                try {
                    // Consulta para obtener los detalles de la venta
                    $sql = "SELECT v.id, v.fecha, v.total, p.nombre AS producto, v.cantidad, v.total AS precio
                            FROM ventas v
                            JOIN productos p ON v.producto_id = p.id
                            WHERE v.id = :venta_id";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute(['venta_id' => $venta_id]);
                    $venta_detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($venta_detalles) {
                        echo "<h2 class='title is-3'>Detalles de la Venta #$venta_id</h2>";
                        echo "<table class='table is-bordered is-striped is-narrow is-hoverable is-fullwidth'>
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                    </tr>
                                </thead>
                                <tbody>";

                        foreach ($venta_detalles as $detalle) {
                            echo "<tr>
                                    <td>{$detalle['producto']}</td>
                                    <td>{$detalle['cantidad']}</td>
                                    <td>\${$detalle['precio']}</td>
                                  </tr>";
                        }

                        echo "</tbody>
                              </table>";
                        echo "<h3 class='total-amount'>Total: \${$venta_detalles[0]['total']}</h3>";
                    } else {
                        echo "<p class='notification is-danger'>No se encontraron detalles para esta venta.</p>";
                    }
                } catch (PDOException $e) {
                    echo '<p class="notification is-danger">Error: ' . $e->getMessage() . '</p>';
                }
            } else {
                try {
                    // Consulta para obtener las ventas
                    $sql = "SELECT v.id, v.fecha, p.nombre AS producto, v.cantidad, v.total
                            FROM ventas v
                            JOIN productos p ON v.producto_id = p.id";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($ventas) {
                        echo "<h2 class='title is-3'>Listado de Ventas</h2>";
                        echo "<table class='table is-bordered is-striped is-narrow is-hoverable is-fullwidth'>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>";

                        foreach ($ventas as $venta) {
                            echo "<tr>
                                    <td>{$venta['id']}</td>
                                    <td>{$venta['fecha']}</td>
                                    <td>{$venta['producto']}</td>
                                    <td>{$venta['cantidad']}</td>
                                    <td>\${$venta['total']}</td>
                                    <td><a class='button is-link' href='?venta_id={$venta['id']}'>Ver detalles</a></td>
                                  </tr>";
                        }

                        echo "</tbody>
                              </table>";
                    } else {
                        echo "<p class='notification is-warning'>No se encontraron ventas.</p>";
                    }
                } catch (PDOException $e) {
                    echo '<p class="notification is-danger">Error: ' . $e->getMessage() . '</p>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
