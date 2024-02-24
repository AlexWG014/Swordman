<?php
session_start();
include 'conectar.php'; 

?>

<?php include 'header.php'; ?>
<?php include 'menu.php'; ?>

<?php

if (!empty($_SESSION)) {
    echo "<h2>¡Gracias por tu compra!</h2>";
    echo "<p>Esperamos que la experiencia haya sido agradable.</p>";

    if ($conn) {
        if (!empty($_SESSION['pedido'])) {
            try {
                $conn->beginTransaction();
    
                $fecha = date("Y-m-d");
                $totalPedido = 0;
    
                foreach ($_SESSION['pedido'] as $detalle) {
                    $totalPedido += $detalle['subtotal'];
                }
    
                $estado = "creado";
    
                $metodoPago = isset($_SESSION['metodo_pago']) ? $_SESSION['metodo_pago'] : "";
                $envio = isset($_SESSION['envio']) ? $_SESSION['envio'] : "";
                $activo = 1;
                $idUsuario = $_SESSION['user']['id']; 
    
                $stmt = $conn->prepare("INSERT INTO pedidos (fecha, total, estado, pago, envio, codUsuario, activo) VALUES (?, ?, ?, ?, ?, ?,? )");
                $stmt->execute([$fecha, $totalPedido, $estado, $metodoPago, $envio, $idUsuario, $activo]);
    
                $idPedido = $conn->lastInsertId();
    
                foreach ($_SESSION['pedido'] as $detalle) {
                    $codArticulo = $detalle['codigo'];
                    $cantidad = $detalle['cantidad'];
                    $precio = $detalle['precio_descuento'];
                    // Insertar los detalles de pedido en la tabla lineapedido
                    $stmt = $conn->prepare("INSERT INTO lineapedido (numPedido, codArticulo, cantidad, precio) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$idPedido, $codArticulo, $cantidad, $precio]);
                }
    
                $conn->commit();
    
                echo "<p>Tu número de pedido es: $idPedido</p>";
                echo "<p>Fecha del pedido: $fecha</p>";
                echo "<p>Envío: $envio</p>";
                echo "<p>Método de pago: $metodoPago</p>";
                echo "<p>En cuanto te lo enviemos, recibirás un mensaje de correo electrónico con el número de seguimiento.</p>";
    
            } catch (PDOException $e) {
                $conn->rollBack();
                echo "Error al procesar el pedido: " . $e->getMessage();
            }
        } else {
            echo "No hay datos de pedido guardados en la sesión.";
        }
    } else {
        echo "Error de conexión a la base de datos.";
    }

} else {
    echo "Por favor, inicia sesión para realizar una compra.";
}

?>

<footer>
    <?php include 'footer.php'; ?>
</footer>
