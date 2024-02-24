<?php
include 'conectar.php';

// Verificar si se ha enviado un código de artículo
if(isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Preparar y ejecutar la consulta para obtener los detalles del artículo
    $stmt = $conn->prepare("SELECT * FROM articulos WHERE codigo = ?");
    $stmt->execute([$codigo]);
    $articulo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el artículo
    if($articulo) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo</title>
    <link rel="stylesheet" href="styles.css">

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
            width: 100%;
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
</head>
<body>
    <div class="container">
        <h1>Editar Artículo</h1>
        <form action="actualizar_articulo.php" method="post">
            <input type="hidden" name="codigo" value="<?= $articulo['codigo'] ?>">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?= $articulo['nombre'] ?>"><br>
            <label for="descripcion">Descripción:</label><br>
            <textarea id="descripcion" name="descripcion"><?= $articulo['descripcion'] ?></textarea><br>
            <label for="precio">Precio:</label><br>
            <input type="number" id="precio" name="precio" value="<?= $articulo['precio'] ?>"><br>
            <label for="descuento">Descuento:</label><br>
            <input type="number" id="descuento" name="descuento" value="<?= $articulo['descuento'] ?>"><br>
            <label for="activo">Activo:</label><br>
            <input type="checkbox" id="activo" name="activo" <?= $articulo['activo'] ? 'checked' : '' ?>><br><br>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>
<?php
    } else {
        echo "No se encontró el artículo.";
    }
} else {
    echo "No se proporcionó un código de artículo.";
}
?>
