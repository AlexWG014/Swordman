<link rel="stylesheet" href="stylesconsulta.css">

<?php
session_start();
include 'conectar.php'; 
?>

<?php include 'menu.php'; ?>
<div style="margin-bottom: 50px;"></div>

<h1>Mis Pedidos</h1>
<div style="margin-bottom: 50px;"></div>

<?php
if (!empty($_SESSION)) {
    // Obtener el ID del usuario en sesión
    $idUsuario = $_SESSION['user']['id'];

    // Consulta para obtener los pedidos del usuario actual
    $stmt = $conn->prepare("SELECT idPedido, fecha, total, CASE estado WHEN 0 THEN 'creado' WHEN 1 THEN 'cancelado' ELSE estado END AS estado_texto FROM pedidos WHERE codUsuario = ?");
    $stmt->execute([$idUsuario]);
    $pedidos = $stmt->fetchAll();

    if ($pedidos) {
        // Mostrar la lista de pedidos
        echo "<table border='1'>";
        echo "<tr><th>Número de Pedido</th><th>Fecha</th><th>Subtotal</th><th>Estado</th><th>Ver Detalles</th><th>Cambiar Estado</th></tr>";
        foreach ($pedidos as $pedido) {
            echo "<tr>";
            echo "<td>" . $pedido['idPedido'] . "</td>";
            echo "<td>" . $pedido['fecha'] . "</td>";
            echo "<td>" . $pedido['total'] . "</td>";
            echo "<td>" . $pedido['estado_texto'] . "</td>";
            echo "<td><a href='ver_detalles.php?id=" . $pedido['idPedido'] . "'><img src='imagenes/detalles.png' alt='Ver Detalles' width='20' height='20'></a></td>"; 
            echo "<td>";
            // Agregar el botón para cambiar el estado de 0 a 1
            if ($pedido['estado_texto'] == 'creado') {
                echo "<a href='cambiar_estado.php?id=" . $pedido['idPedido'] . "&estado=1'><button>Cancelar Pedido</button></a>";
            } else {
                echo "-";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron pedidos para este usuario.";
    }
} else {
    echo "Por favor, inicia sesión para ver tus pedidos.";
}
?>

<div style="margin-bottom: 20px;"></div>
<p>Prefieres volver? <a href="consulta.php">Volver a la consulta</a></p>
<div style="margin-bottom: 20px;"></div>

<footer>
    
    <?php include 'footer.php'; ?>
</footer>
