<link rel="stylesheet" href="styles.css">

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
            <a href="editar_articulo.php?codigo=<?= $articulo['codigo'] ?>">Editar</a>
        </form>
        </div>
    <?php
        echo '</div>';
        echo '<hr>';

    }
    ?>
</body>
</html>
