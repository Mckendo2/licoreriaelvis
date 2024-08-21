<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config.php'; // Conexión a la base de datos

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para verificar las credenciales del usuario
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el usuario y si la contraseña es correcta
    if ($usuario && !empty($usuario['contrasena']) && password_verify($password, $usuario['contrasena'])) {
        // Credenciales correctas, iniciar sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

        // Redirigir según el tipo de usuario
        if ($_SESSION['tipo_usuario'] == 'admin') {
            header("Location: admin/hola_mundo.php");
        } else {
            header("Location: client/index.php");
        }
        exit();
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Iniciar Sesión">
    </form>
</body>
</html>
