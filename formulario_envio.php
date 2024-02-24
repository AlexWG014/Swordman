
<link rel="stylesheet" href="styles.css">

<?php
// Incluir el archivo de conexión a la base de datos
include 'conectar.php';
session_start();


// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar datos del formulario
    
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    // Insertar los datos en la tabla "mensajes"
    $stmt = $conn->prepare("INSERT INTO mensajes (nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mensaje', $mensaje);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Mensaje enviado correctamente.";
        echo '<a href="index.php"><button>Volver al inicio</button></a>'; 
    } else {
        echo "Error al enviar el mensaje.";
    }
}
?>
