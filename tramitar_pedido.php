<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    $_SESSION['login_message'] = "Debes iniciar sesión para tramitar tu pedido.";
    header("Location: proceso_tramitar_pedido.php");
    exit();
}

// El usuario está autenticado, proceder con el tramite del pedido

// Guardar detalles del pedido en la sesión
if (!empty($_SESSION['cesta'])) {
    foreach ($_SESSION['cesta'] as $codigo => $articulo) {
        // Calcular el subtotal con descuento
        $descuento = isset($articulo['descuento']) ? $articulo['descuento'] : 10;
        $subtotal = $articulo['precio'] * $articulo['cantidad'] * (1 - $descuento / 100);
        // Guardar código del artículo, precio con descuento, cantidad y subtotal en la sesión
        $_SESSION['pedido'][$codigo] = array(
            'codigo' => $codigo,
            'precio_descuento' => $articulo['precio'] * (1 - ($articulo['descuento'] / 100)),
            'cantidad' => $articulo['cantidad'],
            'subtotal' => $subtotal
        );
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tramitar Pedido</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos adicionales */
        .linea-separadora {
            border-top: 1px solid #ccc;
            margin-top: 10px;
            margin-bottom: 10px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .total-pedido {
            float: right;
        }
        button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
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

    <div id="content">
            <h2>Artículos en tu cesta:</h2>
            <ul>
            <?php
                $total_pedido = 0; // Inicializar el total del pedido

                // Verificar si hay artículos en la cesta
                if (!empty($_SESSION['cesta'])) {
                    foreach ($_SESSION['cesta'] as $codigo => $articulo) {
                        // Calcular el subtotal con descuento
                        $descuento = isset($articulo['descuento']) ? $articulo['descuento'] : 10;
                        $subtotal = $articulo['precio'] * $articulo['cantidad'] * (1 - $descuento / 100);
                        // Incrementar el total del pedido
                        $total_pedido += $subtotal;
                        // Mostrar los detalles del artículo
                        echo "<li>{$articulo['nombre']} - Cantidad: {$articulo['cantidad']} - Precio: {$articulo['precio']}€ - Descuento: {$descuento}% - Subtotal: {$subtotal}€</li>";
                        // Añadir una línea separadora
                        echo '<div class="linea-separadora"></div>';
                    }
                } else {
                    echo "<li>No hay artículos en tu cesta.</li>";
                }
                ?>
            </ul>

            <!-- Mostrar el total del pedido -->
            <p class="total-pedido"><strong>TOTAL PEDIDO: <?php echo $total_pedido; ?> €</strong></p>

            <div>
                <!-- Botón para volver a la página de la cesta -->

                <form action="tramitar_envio.php" method="post">
                <input type="hidden" name="total_pedido" value="<?php echo $total_pedido; ?>">
                    <button type="submit">Continuar</button>
                </form>
            </div>
        </div>
        <?php include 'right-panel.php'; ?>

    </div>
</main>




<footer>
    <?php include 'footer.php'; ?>
</footer>

</body>
</html>
