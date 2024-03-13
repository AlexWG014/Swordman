<link rel="stylesheet" href="stylesconsulta.css">
    <?php include 'menu.php'; ?>
<?php
session_start();
include 'conectar.php';

// Obtener artículos
$stmt = $conn->prepare("SELECT * FROM articulos");
$stmt->execute();
$articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Artículos</title>
</head>
<body>
    <h1>Consulta de Artículos</h1>

    <?php
    foreach ($articulos as $articulo) {
        echo '<div>';
        echo '<h2>' . $articulo['nombre'] . '</h2>';

        echo '<p>Descripción: ' . $articulo['descripcion'] . '</p>';
        echo '<p>Precio: ' . $articulo['precio'] . '</p>';
        ?>
        <form action="borrar_articulos.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas borrar este artículo? Esta acción no se puede deshacer.')">
            <input type="hidden" name="codigo" value="<?= $articulo['codigo'] ?>">
            <button type="submit"><img src="imagenes/articulos.png" alt="Eliminar Artículo" width="50" height="50"></button>
        </form>
  
        <form action="editar_articulo.php" method="post">
    <a href="editar_articulo.php?codigo=<?= $articulo['codigo'] ?>">
        <img src="imagenes/articulo.png" alt="Editar Artículo" width="50" height="50" alt="Editar artículo">
        </a>
    </form>

        </div>
    <?php
        echo '</div>';
        echo '<hr>';

    }
    ?>
</body>
</html>
<div style="margin-bottom: 20px;"></div>
<p>Prefieres volver? <a href="consulta.php">Volver a la consulta</a></p>
<div style="margin-bottom: 20px;"></div>
