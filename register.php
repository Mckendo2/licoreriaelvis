<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form action="register.php" method="POST">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="correo">Correo:</label><br>
        <input type="email" id="correo" name="correo" required><br><br>

        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br><br>

        <input type="submit" value="Registrarse">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'config.php';

        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)";
        $stmt= $conn->prepare($sql);
        $stmt->execute([$nombre, $correo, $contrasena]);

        echo "Registro exitoso";
    }
    ?>
</body>
</html>
