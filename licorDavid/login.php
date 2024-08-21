<?php
session_start();
require_once "config.php";

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT id, username, password, role FROM usuarios WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        
        if ($stmt->execute()) {
            $stmt->store_result();
            
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password, $role);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        session_start();
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["role"] = $role;

                        // Redireccionar según el rol del usuario
                        if ($role == 'admin') {
                            header("location: admin.php");
                        } else {
                            header("location: index.php");
                        }
                        exit;
                    } else {
                        $password_err = "La contraseña no es válida.";
                    }
                }
            } else {
                $username_err = "No existe una cuenta con ese nombre de usuario.";
            }
        } else {
            echo "Algo salió mal. Por favor, intenta nuevamente.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Licorería</title>
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
        <h2 class="text-center">Iniciar Sesión</h2>
        <form action="login.php" method="post">
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
            <button type="submit" class="btn btn-custom btn-block">Ingresar</button>
        </form>
        <p class="text-center mt-3">¿No tienes una cuenta? <a href="register.php">Crear cuenta</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
