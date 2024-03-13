<link rel="stylesheet" href="styles.css">
<?php include 'header.php'; ?>
<?php include 'menu.php'; ?>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 10%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #333;
        }
    </style>
<?php
include 'conectar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de edición de categoría
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $activo = $_POST['activo'];
    $codCategoriaPadre = $_POST['codCategoriaPadre'];

    // Consulta SQL para actualizar la categoría
    $stmt = $conn->prepare("UPDATE categorias SET nombre = :nombre, activo = :activo, codCategoriaPadre = :codCategoriaPadre WHERE codigo = :codigo");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':activo', $activo);
    $stmt->bindParam(':codCategoriaPadre', $codCategoriaPadre);
    $stmt->bindParam(':codigo', $codigo);

    if ($stmt->execute()) {
        echo "Categoría actualizada correctamente.";
    } else {
        echo "Error al actualizar la categoría: " . $stmt->errorInfo()[2];
    }
} else {
    // Obtener el código de la categoría a editar
    $codigo = $_GET['codigo'];

    // Consulta SQL para obtener los datos de la categoría a editar
    $stmt = $conn->prepare("SELECT * FROM categorias WHERE codigo = :codigo");
    $stmt->bindParam(':codigo', $codigo);
    $stmt->execute();
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

    // Consulta SQL para obtener todas las categorías como opciones para el menú desplegable
    $stmtCategorias = $conn->query("SELECT * FROM categorias");
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
</head>
<body>
    <h2>Editar Categoría</h2>
    <form method="post">
        <input type="hidden" name="codigo" value="<?php echo $categoria['codigo']; ?>">
        Nombre: <input type="text" name="nombre" value="<?php echo $categoria['nombre']; ?>"><br>
        Activo: <input type="checkbox" name="activo" value="1" <?php if ($categoria['activo']) echo 'checked'; ?>><br>
        Categoría Padre:
        <select name="codCategoriaPadre">
            <option value="">Sin categoría padre</option>
            <?php foreach ($categorias as $cat) : ?>
                <option value="<?php echo $cat['codigo']; ?>" <?php if ($cat['codigo'] == $categoria['codCategoriaPadre']) echo 'selected'; ?>><?php echo $cat['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" value="Guardar">
    </form>
</body>
</html>
<?php } ?>

<div style="margin-bottom: 20px;"></div>
<p>Prefieres volver? <a href="consulta.php">Volver a la consulta</a></p>
<div style="margin-bottom: 20px;"></div>

<footer>
    <?php include 'footer.php'; ?>
</footer>