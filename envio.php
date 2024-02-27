<?php
// Incluir el archivo de conexión a la base de datos
include 'conectar.php';

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
        // Aquí puedes agregar el código para enviar un correo electrónico si lo deseas
        echo "Mensaje enviado correctamente.";
    } else {
        echo "Error al enviar el mensaje.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Formulario de Contacto</title>
">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div id="container">
        <?php include 'left-menu.php'; ?>

        <div id="main-content">
            <h2>Formulario de Contacto</h2>

            <form method="post" action="formulario_envio.php">
                <label for="nombre">Nombre *</label>
                <input type="text" name="nombre" required><br>

                <label for="email">Correo Electrónico *</label>
                <input type="email" name="email" required><br>

                <label for="mensaje">Mensaje *</label>
                <textarea name="mensaje" required></textarea><br>

                <input type="submit" value="Enviar Mensaje">
            </form>
        </div>

        <?php include 'right-panel.php'; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
