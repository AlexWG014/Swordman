<?php
include 'conectar.php'; // Asegúrate de tener un archivo que configure la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    // Insertar el mensaje en la base de datos
    $stmt = $conn->prepare("INSERT INTO mensajes (nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mensaje', $mensaje);

    if ($stmt->execute()) {
        // Redirigir a la página de contacto con un mensaje de éxito
        header('Location: contacto.php?mensaje=enviado');
        exit();
    } else {
        echo "Error al guardar el mensaje en la base de datos.";
    }
}
?>
