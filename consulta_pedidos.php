<link rel="stylesheet" href="stylesconsulta.css">

<?php
session_start();
include 'conectar.php'; 
?>

<?php include 'header.php'; ?>
<?php include 'menu.php'; ?>

<h2>Consulta de Pedidos</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
    <label for="dni">Buscar por DNI:</label>
    <input type="text" id="dni" name="dni">
    <label for="num_pedido">Buscar por Número de Pedido:</label>
    <input type="text" id="num_pedido" name="num_pedido">
    <button type="submit">Buscar</button>
</form>
<div style="margin-bottom: 50px;"></div>

<?php
if (!empty($_SESSION)) {
    // Definir la consulta base
    $query = "SELECT u.DNI, p.idPedido, p.fecha, CONCAT(p.total, ' €') AS subtotal, CASE p.estado WHEN 0 THEN 'creado' WHEN 1 THEN 'cancelado' ELSE p.estado END AS estado_texto FROM usuarios u JOIN pedidos p ON u.id = p.codUsuario";

    // Construir la consulta según los parámetros de búsqueda
    if (isset($_GET['dni']) && !empty($_GET['dni'])) {
        $dni = $_GET['dni'];
        $query .= " WHERE u.DNI LIKE '%$dni%'";
    } elseif (isset($_GET['num_pedido']) && !empty($_GET['num_pedido'])) {
        $num_pedido = $_GET['num_pedido'];
        if (strpos($query, 'WHERE') === false) {
            $query .= " WHERE ";
        } else {
            $query .= " AND ";
        }
        $query .= " p.idPedido LIKE '%$num_pedido%'";
    }

    // Ejecutar la consulta
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $pedidos = $stmt->fetchAll();

    if ($pedidos) {
        // Mostrar la lista de pedidos
        echo "<table border='1'>";
        echo "<tr><th>DNI Usuario</th><th>Número de Pedido</th><th>Fecha</th><th>Subtotal</th><th>Estado</th><th>Ver Detalles</th><th>Cambiar Estado</th></tr>";
        foreach ($pedidos as $pedido) {
            echo "<tr>";
            echo "<td>" . $pedido['DNI'] . "</td>";
            echo "<td>" . $pedido['idPedido'] . "</td>";
            echo "<td>" . $pedido['fecha'] . "</td>";
            echo "<td>" . $pedido['subtotal'] . "</td>";
            echo "<td>" . $pedido['estado_texto'] . "</td>";
            echo "<td><a href='ver_detalles.php?id=" . $pedido['idPedido'] . "'>Ver Detalle</a></td>"; // Enlace para ver detalle del pedido
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
        echo "No se encontraron pedidos.";
    }
} else {
    echo "Por favor, inicia sesión para ver los pedidos.";
}
?>

<div style="margin-bottom: 20px;"></div>
<p>Prefieres volver? <a href="consulta.php">Volver a la consulta</a></p>
<div style="margin-bottom: 20px;"></div>

<footer>
    <?php include 'footer.php'; ?>
</footer>
