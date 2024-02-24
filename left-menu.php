<?php
include 'conectar.php';
$busqueda = isset($busqueda) ? $busqueda : '';

// Consultar categorías principales (aquellas sin un padre)
$stmt = $conn->query("SELECT * FROM categorias WHERE codCategoriaPadre IS NULL");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos del menú lateral */
        #left-menu {
            background-color: #333;
            padding: 10px;
            border-right: 2px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #left-menu ul {
            list-style: none;
            padding: 0;
        }

        #left-menu .has-submenu > ul {
            display: none;
            background-color: #444; /* Color de fondo para los submenús */
        }

        #left-menu .has-submenu:hover > ul {
            display: block;
        }

        #left-menu .submenu li {
            padding-left: 10px;
        }

        #left-menu a {
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
        }

        #left-menu a:hover {
            background-color: #555;
        }
    </style>
    <title>Tu Página</title>
</head>
<body>
    
    <div id="left-menu">

    <?php

    echo '<img src="imagenes/logo.png" alt="Texto Alternativo para la imágen" class="Clase para la imágen" id="Identificador para la imágen" width="300px" height="100px">';
    ?>
        <div style="margin-bottom: 50px;"></div>

        <!-- Barra de búsqueda -->
        <form action="buscar.php" method="GET">
                <input type="text" name="busqueda" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>">
                <button type="submit">Buscar</button>
            </form>

        <!-- Menú lateral con categorías y subcategorías -->
        <ul>
            <?php foreach ($categorias as $categoria): ?>
                <li class="has-submenu">
                    <a href="#"><?php echo $categoria['nombre']; ?></a>
                    <?php
                    // Consultar subcategorías de la categoría actual
                    $categoria_id = $categoria['codigo'];
                    $stmt = $conn->prepare("SELECT * FROM categorias WHERE codCategoriaPadre = :categoria_id");
                    $stmt->bindParam(':categoria_id', $categoria_id);
                    $stmt->execute();
                    $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Si hay subcategorías, mostrarlas dentro de un submenu
                    if (!empty($subcategorias)): ?>
                        <ul class="submenu">
                            <?php foreach ($subcategorias as $subcategoria): ?>
                                <li><a href="articulos_subcategoria.php?id=<?php echo $subcategoria['codigo']; ?>"><?php echo $subcategoria['nombre']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <ul class="submenu">
                        <li><a href="articulos_subcategoria.php?id=<?php echo $categoria_id; ?>">Ver todos los artículos</a></li>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
</body>
</html>
