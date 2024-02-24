<link rel="stylesheet" href="stylesconsulta.css">

<?php
session_start();
include 'conectar.php'; 

?>

<?php include 'header.php'; ?>
<?php include 'menu.php'; ?>

<h2>Detalles del Pedido</h2>

<?php

if (!empty($_GET['id'])) {
    $idPedido = $_GET['id'];

    // Consulta para obtener los detalles del pedido
    $stmt_pedido = $conn->prepare("SELECT idPedido, fecha, total, estado FROM pedidos WHERE idPedido = ?");
    $stmt_pedido->execute([$idPedido]);
    $pedido = $stmt_pedido->fetch();

    // Consulta para obtener los detalles de los artículos relacionados con el pedido
    $stmt_articulos = $conn->prepare("SELECT a.codigo, a.nombre, a.descripcion, CONCAT(a.precio, ' €') AS precio, CONCAT(a.descuento, '%') AS descuento, lp.cantidad FROM articulos a INNER JOIN lineapedido lp ON a.codigo = lp.codArticulo WHERE lp.numPedido = ?");
    $stmt_articulos->execute([$idPedido]);
    $articulos = $stmt_articulos->fetchAll();

    if ($pedido) {
        // Mostrar detalles del pedido
        echo "<p>Número de Pedido: " . $pedido['idPedido'] . "</p>";
        echo "<p>Fecha: " . $pedido['fecha'] . "</p>";
        echo "<p>Total: " . $pedido['total'] . "</p>";
        echo "<p>Estado: " . ($pedido['estado'] == 0 ? 'Creado' : 'Cancelado') . "</p>";

        if ($articulos) {
            // Mostrar detalles de los artículos relacionados con el pedido
            echo "<h3>Artículos del Pedido</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Código</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Descuento</th><th>Cantidad</th></tr>";
            foreach ($articulos as $articulo) {
                echo "<tr>";
                echo "<td>" . $articulo['codigo'] . "</td>";
                echo "<td>" . $articulo['nombre'] . "</td>";
                echo "<td>" . $articulo['descripcion'] . "</td>";
                echo "<td>" . $articulo['precio'] . "</td>";
                echo "<td>" . $articulo['descuento'] . "</td>";
                echo "<td>" . $articulo['cantidad'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron artículos para este pedido.";
        }
    } else {
        echo "No se encontró el pedido especificado.";
    }
} else {
    echo "No se especificó un pedido.";
}

?>

<footer>
    <?php include 'footer.php'; ?>
</footer>
