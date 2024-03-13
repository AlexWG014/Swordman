<?php
session_start();
if (!isset($_SESSION['user'])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header('Location: index.php');
    exit(); // Asegúrate de terminar el script después de redirigir
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Categorías</title>
    <link rel="stylesheet" href="stylesconsulta.css">

    <?php include 'menu.php'; ?>
<div style="margin-bottom: 50px;"></div>

</head>
<body>
    <h2>Consulta de Categorías</h2>
    <div style="margin-bottom: 50px;"></div>

    <table>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Activo</th>
            <th>Código de Categoría Padre</th>
            <th>Editar</th>
            <th>Borrar</th>
        </tr>
        <?php
        include 'conectar.php';

        // Consulta para obtener todas las categorías
        $sql = "SELECT * FROM categorias";
        $result = $conn->query($sql);

        if ($result->rowCount() > 0) {
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row["codigo"] . "</td>";
                echo "<td>" . $row["nombre"] . "</td>";
                echo "<td>" . ($row["activo"] ? 'Sí' : 'No') . "</td>";
                echo "<td>" . ($row["codCategoriaPadre"] ?? '-') . "</td>";
                echo "<td>";
                echo "<a href='editar_categoria.php?codigo=" . $row["codigo"] . "' class='btn btn-editar'>Editar</a>";
                echo "</td>";
                echo "<td>";
                echo "<a href='borrar_categoria.php?codigo=" . $row["codigo"] . "' class='btn btn-borrar'>Borrar</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No se encontraron categorías.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<div style="margin-bottom: 20px;"></div>
<p>Prefieres volver? <a href="consulta.php">Volver a la consulta</a></p>
<div style="margin-bottom: 20px;"></div>

<footer>
    <?php include 'footer.php'; ?>
</footer>
