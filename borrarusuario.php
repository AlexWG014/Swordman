<?php
session_start();
include 'conectar.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de borrar usuario
    if (isset($_POST['borrar_usuario'])) {
        $usuario_id = $_POST['usuario_id'];

        // Verificar si el usuario tiene permiso para borrar este perfil
        $usuario_actual = $_SESSION['user'];
        if ($usuario_actual['rol'] == "administrador" || $usuario_actual['id'] == $usuario_id) {
            try {
                $conn->beginTransaction();

                // Primero borramos los artículos asociados a los pedidos del usuario
                $stmtArticulos = $conn->prepare("DELETE FROM lineapedido WHERE numPedido IN (SELECT idPedido FROM pedidos WHERE codUsuario = :usuario_id)");
                $stmtArticulos->bindParam(':usuario_id', $usuario_id);
                $stmtArticulos->execute();

                // Luego borramos los pedidos asociados al usuario
                $stmtPedidos = $conn->prepare("DELETE FROM pedidos WHERE codUsuario = :usuario_id");
                $stmtPedidos->bindParam(':usuario_id', $usuario_id);
                $stmtPedidos->execute();

                // Finalmente borramos al usuario
                $stmtUsuario = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
                $stmtUsuario->bindParam(':id', $usuario_id);
                $stmtUsuario->execute();

                $conn->commit();

                echo "Usuario, pedidos y artículos asociados borrados con éxito.";
                session_destroy();
                header('Location: index.php');
                exit();
            } catch (Exception $e) {
                $conn->rollBack();
                echo "Error al borrar el usuario, sus pedidos y artículos asociados: " . $e->getMessage();
            }
        } else {
            echo "No tienes permisos para borrar este usuario.";
        }
    }
}


?>
