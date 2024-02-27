<?php
session_start();

// Inicializar la cesta si no existe
if (!isset($_SESSION['cesta'])) {
    $_SESSION['cesta'] = [];
}

// Verificar si se ha enviado un artículo para agregar a la cesta
if (isset($_GET['codigo']) && isset($_GET['nombre']) && isset($_GET['precio']) && isset($_GET['cantidad']) && isset($_GET['descuento'])) {
    $codigo = $_GET['codigo'];
    $nombre = $_GET['nombre'];
    $precio = $_GET['precio'];
    $cantidad = $_GET['cantidad'];
    $descuento = $_GET['descuento']; // Asignar el valor del descuento

    // Agregar el artículo a la cesta
    $_SESSION['cesta'][$codigo] = [
        'nombre' => $nombre,
        'precio' => $precio,
        'cantidad' => $cantidad,
        'descuento' => $descuento
    ];
}


// Verificar si se ha enviado un formulario para actualizar la cantidad de un artículo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['codigo']) && isset($_POST['cantidad'])) {
        $codigo = $_POST['codigo'];
        $cantidad = $_POST['cantidad'];

        // Actualizar la cantidad del artículo en la cesta
        if ($cantidad > 0) {
            $_SESSION['cesta'][$codigo]['cantidad'] = $cantidad;
        } else {
            // Si la cantidad es 0 o menos, eliminar el artículo de la cesta
            unset($_SESSION['cesta'][$codigo]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Cesta de Compra</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos adicionales */

        .cesta-container {
            text-align: center;
            margin-bottom: 20px; /* Ajustar el margen entre el título y los elementos */
        }

        .cesta ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .cesta li {
            border-bottom: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center; /* Centrar el contenido */
        }

        .cesta li:last-child {
            border-bottom: none;
        }

        input[type="number"] {
            width: 50px;
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

    <div id="main-content">

    <div class="cesta-container">
        <h1>Cesta de Compra</h1>
    </div>
        <?php                 $total_pedido = 0; // Inicializar el total del pedido ?>
    <div class="cesta">
        <?php if (empty($_SESSION['cesta'])) : ?>
            <p>La cesta está vacía.</p>
        <?php else : ?>
            <ul>
                
            <?php foreach ($_SESSION['cesta'] as $codigo => $articulo) :
                 ?>
                <li>
                    
                    <form method="post" action="">
                        <?php 
                            $descuento = isset($articulo['descuento']) ? $articulo['descuento'] : 10; // Verificar si 'descuento' está definido
                            $subtotal = $articulo['precio'] * $articulo['cantidad'] * (1 - $descuento / 100);
                            echo "Artículo: $articulo[nombre] - Cantidad: $articulo[cantidad] - Precio: $articulo[precio]€ - Descuento: $descuento% - Subtotal: $subtotal €";
                            $total_pedido += $subtotal;

                        ?>
                        <br>
                        <input type="number" name="cantidad" value="<?php echo $articulo['cantidad']; ?>" min="1">
                        <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
                        <button type="submit" name="submit">Actualizar Cantidad</button>
                    </form>
                    <div style="margin-bottom: 5px;"></div>

                    <button type="button" onclick="eliminarArticulo('<?php echo $codigo; ?>')">Eliminar Artículo</button>
                </li>
            <?php endforeach; ?>
            </ul>
            <p class="total-pedido"><strong>TOTAL PEDIDO: <?php echo $total_pedido; ?> €</strong></p>

        <?php endif; ?>
    </div>
    </div>


    <?php include 'right-panel.php'; ?>

</main>



<script>
    // Función para eliminar un artículo de la cesta
    function eliminarArticulo(codigo) {
        if (confirm("¿Estás seguro de que deseas eliminar este artículo de la cesta?")) {
            window.location.href = "eliminar_articulo.php?codigo=" + codigo;
        }
    }
</script>

<?php if (!empty($_SESSION['cesta'])) : ?>
<div>
        <!-- Botón "Seguir comprando" -->
        <form action="index.php">
            <button type="submit">Seguir comprando</button>
        </form>
        <div style="margin-bottom: 5px;"></div>

        <!-- Botón "Realizar Pedido" -->
        <form action="tramitar_pedido.php">
            <button type="submit">Realizar Pedido</button>
        </form>
    </div>
<?php endif; ?>
<div style="margin-bottom: 10px;"></div>

<footer>
    <?php include 'footer.php'; ?>
</footer>

</body>
</html>
