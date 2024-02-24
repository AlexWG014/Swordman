<?php
// Verificar si la sesión no está iniciada antes de iniciarla
if (!isset($_SESSION)) {
    session_start();
}

// Configuración de la base de datos
$servername = "f80b6byii2vwv8cx.chr7pe7iynqr.eu-west-1.rds.amazonaws.com";
$username = "i9nsm9m6b1e7cet6";
$password = "eyexxk3nidq8i9rp";
$dbname = "rdvkbtzj2rcu9gqb";

// Intentar establecer la conexión mediante PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
