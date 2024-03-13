<?php
include 'conectar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Quiénes Somos</title>

</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div id="container">
        <?php include 'left-menu.php'; ?>

        <div id="main-content">
        <h2>¿Quiénes Somos?</h2>

            <section id="quienes-somos">
                <p>Tener ideas novedosas no siempre es algo que las personas aceptan. Esto es algo que la empresa NotCo tuvo en mente cuando lanzó al mercado su línea de alimentos libres de productos animales. La estrategia de esta empresa ha consistido en conectar con las personas mediante mensajes directos y provocativos que se acompañan de un diseño original y de colores llamativos.</p>

            </section>
        </div>

        <?php include 'right-panel.php'; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>