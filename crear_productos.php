<?php
include 'conectar.php';

// Obtener todas las categorías desde la base de datos
$stmt = $conn->prepare("SELECT * FROM categorias");
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $descuento = $_POST['descuento'];
    $activo = isset($_POST['activo']) ? 1 : 0; // Verifica si el checkbox está marcado

    // Procesar las imágenes
    $imagenes = [];
    if (!empty($_FILES['imagenes']['name'][0])) {
        $upload_directory = 'imagenes/articulos/';

        foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
            $imagen = $_FILES['imagenes']['name'][$key];
            $target_path = $upload_directory . $imagen;

            if (move_uploaded_file($tmp_name, $target_path)) {
                $imagenes[] = $imagen;
            } else {
                echo "Error al cargar la imagen $imagen.";
            }
        }
    }

    // Insertar el artículo en la base de datos
    $stmt = $conn->prepare("INSERT INTO articulos (codigo, nombre, descripcion, precio, descuento, activo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $codigo);
    $stmt->bindParam(2, $nombre);
    $stmt->bindParam(3, $descripcion);
    $stmt->bindParam(4, $precio);
    $stmt->bindParam(5, $descuento);
    $stmt->bindParam(6, $activo);

    if ($stmt->execute()) {
        $articulo_id = $conn->lastInsertId();

        // Insertar las imágenes en la tabla imagenes_articulos
        // Insertar las imágenes en la tabla imagenes_articulos
        foreach ($imagenes as $imagen) {
            $stmtImagenes = $conn->prepare("INSERT INTO imagenes_articulos (articulo_codigo, ruta_imagen) VALUES (?, ?)");
            $stmtImagenes->bindParam(1, $codigo);
            $stmtImagenes->bindParam(2, $imagen);
            $stmtImagenes->execute();
        }
            

        // Insertar las relaciones entre el artículo y las categorías seleccionadas
        if (isset($_POST['categorias']) && is_array($_POST['categorias'])) {
            foreach ($_POST['categorias'] as $categoria_id) {
                $stmtRelacion = $conn->prepare("INSERT INTO articulos_categorias (articulo_codigo, categoria_codigo) VALUES (?, ?)");
                $stmtRelacion->bindParam(1, $codigo);
                $stmtRelacion->bindParam(2, $categoria_id);
                $stmtRelacion->execute();
            }
        }

        echo "Artículo agregado correctamente.";
    } else {
        echo "Error al agregar el artículo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <title>Crear Artículo</title>
</head>
<body>
    <h2>Crear Artículo</h2>
    <form action="crear_productos.php" method="post" enctype="multipart/form-data">
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" required><br>
        
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required></textarea><br>

        <label for="categoria">Categorías:</label><br>

        <?php
        // Generar casillas de verificación para cada categoría
        foreach ($categorias as $categoria) {
            echo '<input type="checkbox" name="categorias[]" value="' . $categoria['codigo'] . '"> ' . $categoria['nombre'] . '<br>';
        }
        ?>
        
        <label for="precio">Precio:</label>
        <input type="text" name="precio" required><br>

        <label for="imagenes">Imágenes:</label>
        <input type="file" name="imagenes[]" multiple required><br> <!-- Permitir múltiples imágenes -->

        <label for="descuento">Descuento:</label>
        <input type="text" name="descuento" required><br>

        <label for="activo">Activo:</label>
        <input type="checkbox" name="activo" checked><br>

        <input type="submit" value="Crear Artículo">

        <button>
            <a href="index.php">Volver al inicio</a>
        </button>

    </form>
</body>
</html>
