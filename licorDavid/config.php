<?php
$servername = "localhost";
$username = "root"; // Cambia esto si tienes un usuario de MySQL diferente
$password = ""; // Coloca tu contraseña de MySQL
$dbname = "licoreria";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
