<?php
session_start();

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['user'])) {
    // El usuario ya ha iniciado sesión, redirigirlo a tramitar_pedido.php
    header("Location: tramitar_pedido.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<?php include 'header.php'; ?>
    <?php include 'menu.php'; ?></head>
<body>
    <!-- Contenido del formulario de inicio de sesión para nuevo cliente -->
    <div>
        <h1>Nuevo Cliente</h1>
        <p>¿Necesitas una cuenta?</p>
        <p>Al crear una cuenta podrás realizar tus compras rápidamente.</p>

        <!-- Enlace para registrarse -->
        <p><a href="registro.php">Registrarse</a></p>
    </div>

    <!-- Contenido del formulario de inicio de sesión para clientes existentes -->
    <div>
        <h1>Ya soy cliente</h1>
        <p>Por favor, inicia sesión para tramitar tu pedido.</p>

        <!-- Formulario de inicio de sesión -->
        <form action="comprobarlogin.php" method="post">
            <input type="text" name="username" placeholder="Usuario">
            <input type="password" name="password" placeholder="Contraseña">
            <input type="submit" value="Iniciar Sesión">
        </form>

        <!-- Enlace para recuperar contraseña -->
        <p><a href="recuperar_contraseña.php">¿Has olvidado tu contraseña?</a></p>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
