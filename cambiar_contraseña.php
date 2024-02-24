<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div class="container">
        <?php include 'left-menu.php'; ?>

        <div id="main-content">
        <h2>Cambiar Contraseña</h2>

            <section id="cambiar_contraseña">
                <form method="post" action="procesar_cambio_contraseña.php">
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <input type="hidden" name="id" value="<?php echo $_SESSION['usuario_id']; ?>">
                    <?php endif; ?>
                    
                    <label for="password">Nueva Contraseña:</label>
                    <input type="password" name="password" required>

                    <label for="confirm_password">Confirmar Contraseña:</label>
                    <input type="password" name="confirm_password" required>

                    <input type="submit" value="Cambiar Contraseña">
                    </form>
            </section>
        </div>
        <?php include 'right-panel.php'; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>