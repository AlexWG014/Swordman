<?php
include 'conectar.php';

// Validar y obtener el ID de la subcategoría
$subcategoria_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($subcategoria_id === false) {
    echo "ID de subcategoría no válido.";
    exit;
}



// Consultar y obtener el nombre de la subcategoría
$stmt = $conn->prepare("SELECT nombre FROM categorias WHERE codigo = :subcategoria_id");
$stmt->bindParam(':subcategoria_id', $subcategoria_id, PDO::PARAM_INT);
if (!$stmt->execute()) {
    echo "Error al recuperar la subcategoría.";
    exit;
}
$subcategoria = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró la subcategoría y asignar el nombre a $nombre_subcategoria
if ($subcategoria) {
    $nombre_subcategoria = $subcategoria['nombre'];
} else {
    // Si no se encuentra la subcategoría, asignar un valor predeterminado o mostrar un mensaje de error
    $nombre_subcategoria = "Subcategoría no dencontrada";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nombre_subcategoria; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos CSS */
        .articulo {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            width: 300px;
            display: inline-block;
        }
        
        .articulo h2 {
            margin-bottom: 5px;
        }

        .articulo p {
            margin-bottom: 5px;
        }

        .precio-original {
            text-decoration: line-through;
            color: #888;
        }

        .precio-descuento {
            font-weight: bold;
            color: #c00;
        }

        .boton-compra {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            margin-right: 10px; /* Espacio a la derecha */
        }

        .disponibilidad {
            font-weight: bold;
            margin-top: 5px;
        }

        .en-stock {
            color: green;
        }

        .sin-existencias {
            color: orange;
        }

        .carousel-item img {
            width: 100%;
            height: auto;
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>
</header>

<main>
    <div id="container">
        <?php include 'left-menu.php'; ?>

        <div id="main-content">
            <h1><?php echo $nombre_subcategoria; ?></h1>

            <div class="articulos-container">
                <?php
                // Consultar los artículos de la subcategoría
                $stmt = $conn->prepare("SELECT a.codigo, a.nombre, a.descripcion, a.precio, a.descuento, a.activo, ia.ruta_imagen 
                    FROM articulos AS a 
                    LEFT JOIN imagenes_articulos AS ia ON a.codigo = ia.articulo_codigo 
                    JOIN articulos_categorias AS ac ON a.codigo = ac.articulo_codigo 
                    WHERE ac.categoria_codigo = :subcategoria_id");
                $stmt->bindParam(':subcategoria_id', $subcategoria_id, PDO::PARAM_INT);
                if (!$stmt->execute()) {
                    echo "Error al recuperar los artículos.";
                    exit;
                }
                $articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Agrupar las imágenes por artículo
                $articulos_con_imagenes = [];
                foreach ($articulos as $articulo) {
                    $articulo_id = $articulo['codigo'];
                    if (!isset($articulos_con_imagenes[$articulo_id])) {
                        $articulos_con_imagenes[$articulo_id] = [
                            'codigo' => $articulo['codigo'],
                            'nombre' => $articulo['nombre'],
                            'descripcion' => $articulo['descripcion'],
                            'precio' => $articulo['precio'],
                            'descuento' => $articulo['descuento'],
                            'activo' => $articulo['activo'],
                            'imagenes' => [],
                        ];
                    }
                    if ($articulo['ruta_imagen']) {
                        $articulos_con_imagenes[$articulo_id]['imagenes'][] = $articulo['ruta_imagen'];
                    }
                }

                // Dividir los artículos en grupos de 4 por página
                $articulos_por_pagina = array_chunk($articulos_con_imagenes, 4);

                // Obtener el número de página actual
                $pagina_actual = isset($_GET['page']) ? $_GET['page'] : 1;

                // Mostrar los artículos de la página actual
                $articulos_pagina_actual = isset($articulos_por_pagina[$pagina_actual - 1]) ? $articulos_por_pagina[$pagina_actual - 1] : [];

                // Mostrar los artículos en recuadros
                foreach ($articulos_pagina_actual as $articulo_id => $articulo) {
                    if ($articulo_id % 2 == 0) {
                        echo '<div class="row">';
                    }
                    echo '<div class="col-md-6">';
                    echo '<div class="articulo">';
                    // Carrusel de imágenes asociadas al artículo
                    echo '<div id="carousel-' . $articulo_id . '" class="carousel slide" data-ride="carousel">';
                    echo '<div class="carousel-inner">';
                    foreach ($articulo['imagenes'] as $index => $imagen) {
                        echo '<div class="carousel-item' . ($index === 0 ? ' active' : '') . '">';
                        echo '<img src="imagenes/articulos/' . $imagen . '" class="d-block w-100" alt="Imagen ' . $index . '">';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '<a class="carousel-control-prev" href="#carousel-' . $articulo_id . '" role="button" data-slide="prev">';
                    echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                    echo '<span class="sr-only">Previous</span>';
                    echo '</a>';
                    echo '<a class="carousel-control-next" href="#carousel-' . $articulo_id . '" role="button" data-slide="next">';
                    echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                    echo '<span class="sr-only">Next</span>';
                    echo '</a>';
                    echo '</div>';
                    // Fin del carrusel

                    // Calcular el precio con descuento
                    $precio_descuento = $articulo['precio'] * (1 - $articulo['descuento'] / 100);

                    // Mostrar la disponibilidad
                    $disponibilidad = $articulo['activo'] == 1 ? "En Stock" : "Sin Existencias";
                    $color_disponibilidad = $articulo['activo'] == 1 ? "en-stock" : "sin-existencias";

                    echo '<h2>' . $articulo['nombre'] . '</h2>';
                    echo '<p>' . $articulo['descripcion'] . '</p>';
                    echo '<p class="precio-original">Precio Original: ' . $articulo['precio'] . '€</p>';
                    echo '<p class="precio-descuento">Precio Final: ' . $precio_descuento . '€ (Dto: ' . $articulo['descuento'] . '%)</p>';
                    echo '<p class="disponibilidad ' . $color_disponibilidad . '">' . $disponibilidad . '</p>';
                    echo '<a href="detalle_articulo.php?codigo=' . htmlspecialchars($articulo['codigo']) . '" class="boton-compra">Ver Detalles</a>';
                    echo '<div style="margin-bottom: 5px;"></div>';
                    echo '<a href="cesta.php?codigo=' .  $articulo['codigo'] . '&nombre=' . urlencode($articulo['nombre']) . '&precio=' . $articulo['precio'] . '&cantidad=1&descuento=' . $articulo['descuento'] . '" class="boton-compra">Agregar a la cesta</a>';
                    echo '</div>';
                    echo '</div>';
                    if ($articulo_id % 2 != 0 || $articulo_id == count($articulos_pagina_actual) - 1) {
                        echo '</div>';
                    }
                }
                ?>
            </div>
            

            <!-- Paginación -->
            <nav aria-label="Paginación">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= count($articulos_por_pagina); $i++) : ?>
                        <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                            <a class="page-link" href="?id=<?php echo $subcategoria_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <!-- Fin de la paginación -->

        </div>

        <?php include 'right-panel.php'; ?>
    </div>
</main>

<footer>
    <?php include 'footer.php'; ?>
</footer>

<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>