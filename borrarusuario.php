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

            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $usuario_id);

            if ($stmt->execute()) {
                echo "Usuario borrado con éxito.";
                session_destroy();
                header('Location: index.php');

                exit();
            } else {
                echo "Error al borrar el usuario: " . $stmt->errorInfo()[2];
            }
        } else {
            echo "No tienes permisos para borrar este usuario.";
        }
    }
}
?>
