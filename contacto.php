<!-- contacto.php -->
<?php
// Incluir el archivo de conexión a la base de datos
include 'conectar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Agrega el script de Google Maps -->

</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="container">
        <?php include 'left-menu.php'; ?>

    <div class="container">
        <h2>Contacto</h2>
        <!-- Mapa de Google Maps -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d25060.462375063682!2d-0.7103246210551475!3d38.26656078663542!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd63b42c4ece57a3%3A0xd9a5de7c6be724dd!2sElche%2C%20Alicante!5e0!3m2!1ses!2ses!4v1708540877997!5m2!1ses!2ses" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

        <p>¿Tienes alguna sugerencia o duda sin resolver? Haznoslo saber!</p>

        <!-- Formulario de contacto -->
        <form method="post" action="procesar_contacto.php">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br>

            <label for="mensaje">Mensaje:</label>
            <textarea name="mensaje" required></textarea><br>

            <input type="submit" value="Enviar Mensaje">
        </form>

        <!-- Enlaces a redes sociales -->
        <div class="redes-sociales">
            <a href="https://www.facebook.com/tupagina" target="_blank"><img src="imagenes/Facebook.png" alt="Facebook"width="60px" height="50px"></a>
            <a href="https://twitter.com/tupagina" target="_blank"><img src="imagenes/X.png" alt="Twitter"width="50px" height="50px"></a>
            <!-- Agrega otros enlaces a redes sociales según sea necesario -->
        </div>
    </div>
        <?php include 'right-panel.php'; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>



