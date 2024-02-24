<?php
include 'conectar.php';

// Obtener el término de búsqueda y limpiarlo para evitar inyecciones SQL
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de búsqueda: <?php echo htmlspecialchars($busqueda); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
        .precio-original {
            text-decoration: line-through;
            color: #888;
        }

        .precio_descuento {
            font-weight: bold;
            color: #c00;
        }

        /* Estilos CSS */
        /* Agrega aquí los estilos si es necesario */
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
            <h1>Resultados de búsqueda: "<?php echo htmlspecialchars($busqueda); ?>"</h1>

            <div class="articulos-container">
                <?php
                if (!empty($busqueda)) {
                    // Consultar los artículos que coincidan con el término de búsqueda
                    $stmt = $conn->prepare("SELECT a.codigo, a.nombre, a.descripcion, a.precio, a.descuento, a.activo, ia.ruta_imagen 
                        FROM articulos AS a 
                        LEFT JOIN imagenes_articulos AS ia ON a.codigo = ia.articulo_codigo 
                        WHERE a.nombre LIKE :busqueda OR a.descripcion LIKE :busqueda");
                    $stmt->bindValue(':busqueda', '%' . $busqueda . '%', PDO::PARAM_STR);
                    if ($stmt->execute()) {
                        $articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Agrupar los artículos por su código
                        $articulos_agrupados = [];
                        foreach ($articulos as $articulo) {
                            $codigo_articulo = $articulo['codigo'];
                            if (!isset($articulos_agrupados[$codigo_articulo])) {
                                $articulos_agrupados[$codigo_articulo] = [
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
                                $articulos_agrupados[$codigo_articulo]['imagenes'][] = $articulo['ruta_imagen'];
                            }
                        }

                        // Mostrar los artículos y sus imágenes en un carrusel
                        foreach ($articulos_agrupados as $codigo_articulo => $articulo) {
                            echo '<div class="articulo">';
                            echo '<div id="carousel-' . $codigo_articulo . '" class="carousel slide" data-ride="carousel">';
                            echo '<div class="carousel-inner">';
                            $first = true;
                            foreach ($articulo['imagenes'] as $index => $imagen) {
                                echo '<div class="carousel-item' . ($first ? ' active' : '') . '">';
                                echo '<img src="imagenes/articulos/' . htmlspecialchars($imagen) . '" class="d-block w-100" alt="Imagen del artículo">';
                                echo '</div>';
                                $first = false;
                            }
                            echo '</div>';
                            echo '<a class="carousel-control-prev" href="#carousel-' . $codigo_articulo . '" role="button" data-slide="prev">';
                            echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                            echo '<span class="sr-only">Previous</span>';
                            echo '</a>';
                            echo '<a class="carousel-control-next" href="#carousel-' . $codigo_articulo . '" role="button" data-slide="next">';
                            echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                            echo '<span class="sr-only">Next</span>';
                            echo '</a>';
                            echo '</div>';
                            echo '<h2>' . htmlspecialchars($articulo['nombre']) . '</h2>';
                            echo '<p>' . htmlspecialchars($articulo['descripcion']) . '</p>';
                            echo '<p>Precio original: ' . htmlspecialchars($articulo['precio']) . '€</p>';
                            // Calcular el precio con descuento
                            $precio_descuento = $articulo['precio'] * (1 - $articulo['descuento'] / 100);
                            echo '<p>Precio: ' . htmlspecialchars($precio_descuento) . '€ (Dto: ' . htmlspecialchars($articulo['descuento']) . '%)</p>';
                            // Mostrar disponibilidad
                            $disponibilidad = $articulo['activo'] == 1 ? "En Stock" : "Sin Existencias";
                            echo '<p>' . htmlspecialchars($disponibilidad) . '</p>';
                            
                            // Carrusel de imágenes

                            // Fin del carrusel
                            
                            echo '<a href="detalle_articulo.php?codigo=' . htmlspecialchars($codigo_articulo) . '" class="boton-compra">Ver Detalles</a>';
                            echo '<a href="agregar_a_la_cesta.php?codigo=' .  $articulo['codigo'] . '" class="boton-compra">Agregar a la cesta</a>';                 

                            echo '</div>';
                        }
                    } else {
                        echo "Error al ejecutar la consulta.";
                    }
                } else {
                    echo "<p>No se ha especificado ningún término de búsqueda.</p>";
                }
                ?>
            </div>
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