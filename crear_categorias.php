<?php include 'menu.php'; ?>
<div style="margin-bottom: 50px;"></div>

<?php
include 'conectar.php';

// Obtener todas las categorías padre de la base de datos
$stmt_categorias_padre = $conn->prepare("SELECT * FROM categorias WHERE codCategoriaPadre IS NULL");
$stmt_categorias_padre->execute();
$categorias_padre = $stmt_categorias_padre->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $activo = isset($_POST["activo"]) ? 1 : 0;
    
    // Verificar si se seleccionó una categoría padre
    $codCategoriaPadre = !empty($_POST["codCategoriaPadre"]) ? $_POST["codCategoriaPadre"] : null;

    // Obtener el último código de categoría
    $stmt_last_code = $conn->prepare("SELECT MAX(codigo) AS max_codigo FROM categorias");
    $stmt_last_code->execute();
    $last_code_row = $stmt_last_code->fetch(PDO::FETCH_ASSOC);
    $last_code = $last_code_row['max_codigo'];

    // Incrementar el código para obtener el nuevo código de categoría
    $new_code = $last_code + 1;

    // Insertar la nueva categoría/subcategoría en la base de datos
    $stmt = $conn->prepare("INSERT INTO categorias (codigo, nombre, activo, codCategoriaPadre) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $new_code);
    $stmt->bindParam(2, $nombre);
    $stmt->bindParam(3, $activo);
    $stmt->bindParam(4, $codCategoriaPadre, PDO::PARAM_INT); // Se especifica el tipo de dato

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Categoría/Subcategoría creada exitosamente.";
    } else {
        echo "Error al crear la categoría/subcategoría.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Categoría/Subcategoría</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Crear Categoría/Subcategoría</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="activo">Activo:</label>
        <input type="checkbox" id="activo" name="activo" checked><br>
        <label for="codCategoriaPadre">Categoría Padre:</label><br>
        <select id="codCategoriaPadre" name="codCategoriaPadre">
            <option value="">Sin Categoría Padre</option>
            <?php foreach ($categorias_padre as $categoria_padre): ?>
                <option value="<?php echo $categoria_padre['codigo']; ?>"><?php echo $categoria_padre['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" value="Crear">
        <button>
            <a href="index.php">Volver al inicio</a>
        </button>
    </form>
</body>
</html>
<div style="margin-bottom: 20px;"></div>
<p>Prefieres volver? <a href="consulta.php">Volver a la consulta</a></p>
<div style="margin-bottom: 20px;"></div>
<footer>
    <?php include 'footer.php'; ?>
</footer>