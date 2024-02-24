<link rel="stylesheet" href="styles.css">

<?php
session_start();
include 'conectar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el código del artículo está presente
    if (isset($_POST["codigo"])) {
        $codigo_articulo = $_POST["codigo"];

        // Consulta para eliminar el artículo
        $stmt = $conn->prepare("DELETE FROM articulos WHERE codigo = ?");
        $stmt->execute([$codigo_articulo]);

        // Redireccionar a la página principal después de eliminar
        header("Location: consulta_articulos.php");
        exit;
    }
}
?>
