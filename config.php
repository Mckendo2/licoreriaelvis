<?php
$host = 'localhost';
$db = 'licorxd';
$user = 'root';
$pass = ''  ;

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
