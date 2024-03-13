<link rel="stylesheet" href="stylesconsulta.css">
<?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>
<?php
include 'conectar.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['codigo'])) {
    // Obtener el código de la categoría a borrar
    $codigo = $_GET['codigo'];

    // Consulta SQL para borrar la categoría
    $stmt = $conn->prepare("DELETE FROM categorias WHERE codigo = :codigo");
    $stmt->bindParam(':codigo', $codigo);

    if ($stmt->execute()) {
        echo "Categoría eliminada correctamente.";
    } else {
        echo "Error al eliminar la categoría: " . $stmt->errorInfo()[2];
    }
} else {
    echo "No se proporcionó el código de la categoría a borrar.";
}

?>

<div style="margin-bottom: 20px;"></div>
<p>Prefieres volver? <a href="consulta.php">Volver a la consulta</a></p>
<div style="margin-bottom: 20px;"></div>

<footer>
    <?php include 'footer.php'; ?>
</footer>