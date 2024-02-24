<?php
session_start();
include 'conectar.php';

if (!empty($_GET['id']) && isset($_GET['estado'])) {
    $idPedido = $_GET['id'];
    $nuevoEstado = $_GET['estado'];

    try {
        // Actualizar el estado del pedido en la base de datos
        $stmt = $conn->prepare("UPDATE pedidos SET estado = ? WHERE idPedido = ?");
        $stmt->execute([$nuevoEstado, $idPedido]);

        // Redirigir de vuelta a la página de mis_pedidos.php después de cambiar el estado
        header("Location: mis_pedidos.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al cambiar el estado del pedido: " . $e->getMessage();
    }
} else {
    echo "ID de pedido o estado no proporcionado.";
}
?>
