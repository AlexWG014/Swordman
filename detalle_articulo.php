<?php
// Incluir archivo de conexión a la base de datos
include 'conectar.php';

// Obtener el código del artículo de la URL
if (isset($_GET['codigo'])) {
    $articulo_codigo = $_GET['codigo'];

    // Consultar los detalles del artículo
    $stmt = $conn->prepare("SELECT * FROM articulos WHERE codigo = :articulo_codigo");
    $stmt->bindParam(':articulo_codigo', $articulo_codigo);
    $stmt->execute();
    $articulo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Consultar las imágenes relacionadas con el artículo
    $stmtImagenes = $conn->prepare("SELECT ruta_imagen FROM imagenes_articulos WHERE articulo_codigo = :articulo_codigo");
    $stmtImagenes->bindParam(':articulo_codigo', $articulo_codigo);
    $stmtImagenes->execute();
    $imagenes = $stmtImagenes->fetchAll(PDO::FETCH_COLUMN);

    // Verificar si se encontró el artículo
    if (!$articulo) {
        // Si no se encuentra el artículo, redirigir a una página de error o mostrar un mensaje
        header("Location: error.php");
        exit;
    }
} else {
    // Si no se proporciona ningún código de artículo en la URL, redirigir a una página de error o mostrar un mensaje
    header("Location: error.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Artículo</title>
    <!-- Agregar estilos CSS aquí -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos CSS */
        .carousel {
            width: 100%;
            overflow: hidden;
        }

        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease;
        }

        .carousel-item {
            min-width: 100%;
            flex: 0 0 auto;
        }

        .boton-compra {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            margin-top: 10px;
        }

        .precio-original {
            text-decoration: line-through;
            color: #888;
        }

        .precio-descuento {
            font-weight: bold;
            color: #c00;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div id="container">
        <?php include 'left-menu.php'; ?>

        <div id="main-content">
            <h1>Detalles del Artículo</h1>
            <?php
            // Verificar si se encontró el artículo antes de mostrar los detalles
            if ($articulo) {
                // Mostrar el carrusel con las imágenes relacionadas con el artículo
                if (!empty($imagenes)) {
                    echo '<div class="carousel">';
                    echo '<div class="carousel-inner">';
                    foreach ($imagenes as $imagen) {
                        echo '<div class="carousel-item">';
                        echo '<img src="' . $imagen . '" alt="' . $articulo['nombre'] . '">';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<p>No hay imágenes disponibles para este artículo.</p>';
                }

                // Calcular el precio con descuento
                $precio_descuento = $articulo['precio'] * (1 - $articulo['descuento'] / 100);

                echo '<p><strong>Nombre:</strong> ' . $articulo['nombre'] . '</p>';
                echo '<p><strong>Descripción:</strong> ' . $articulo['descripcion'] . '</p>';
                echo '<p class="precio-original">Precio Original: $' . $articulo['precio'] . '</p>';
                echo '<p class="precio-descuento">Precio Final: $' . $precio_descuento . ' (Dto: ' . $articulo['descuento'] . '%)</p>';
                echo '<a href="cesta.php?codigo=' .  $articulo['codigo'] . '&nombre=' . urlencode($articulo['nombre']) . '&precio=' . $articulo['precio'] . '&cantidad=1&descuento=' . $articulo['descuento'] . '" class="boton-compra">Agregar a la cesta</a>';
            } else {
                echo "<p>No se encontró el artículo.</p>";
            }
            ?>
        </div>

        <?php include 'right-panel.php'; ?>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
