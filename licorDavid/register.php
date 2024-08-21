<?php
require_once "config.php";

$username = $password = $role = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificación de usuario
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, ingrese un nombre de usuario.";
    } else {
        $sql = "SELECT id FROM usuarios WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $username_err = "Este nombre de usuario ya está tomado.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Algo salió mal. Por favor, intenta nuevamente.";
            }
            $stmt->close();
        }
    }

    // Verificación de contraseña
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, ingrese una contraseña.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Asignación de rol (solo administradores pueden asignar roles)
    $role = "cliente";  // Por defecto, todos los usuarios son "cliente"
    
    if (empty($username_err) && empty($password_err)) {
        $sql = "INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $param_username, $param_password, $param_role);

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_role = $role;

            if ($stmt->execute()) {
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $stmt->insert_id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $role;

                header("location: index.php");
            } else {
                echo "Algo salió mal. Por favor, intenta nuevamente.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Licorería</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Color pastel de fondo */
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #6c757d; /* Color pastel grisáceo */
            color: #ffffff;
        }
        .btn-custom:hover {
            background-color: #5a6268; /* Color pastel gris oscuro */
        }
        .alert-custom {
            color: #dc3545; /* Color rojo para errores */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Crear Cuenta</h2>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" class="form-control" required>
                <div class="alert-custom"><?php echo $username_err; ?></div>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div class="alert-custom"><?php echo $password_err; ?></div>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Crear Cuenta</button>
        </form>
        <p class="text-center mt-3">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
