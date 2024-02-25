<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiénes Somos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div id="container">
        <?php include 'left-menu.php'; ?>

        <div id="main-content">


            <h1>Recuperar Contraseña</h1>

            <form method="post" action="procesar_recuperacion.php">
            <label for="dni">DNI:</label>
            <input type="text" name="dni" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" required>

            <input type="submit" value="Recuperar Contraseña">
            </section>
        </div>

        <?php include 'right-panel.php'; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
