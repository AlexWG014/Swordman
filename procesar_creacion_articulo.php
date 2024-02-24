<link rel="stylesheet" href="styles.css">

<?php
session_start();
include 'conectar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario y realizar la validación
    $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $precio = isset($_POST['precio']) ? $_POST['precio'] : '';
    $descuento = isset($_POST['descuento']) ? $_POST['descuento'] : '';
    $activo = isset($_POST['activo']) ? 1 : 0;
    $subcategorias = isset($_POST['categorias']) ? $_POST['categorias'] : [];

    // Procesar las imágenes
    $imagenes = isset($_FILES['imagenes']) ? $_FILES['imagenes'] : [];
    $rutaImagenes = [];

    // Validar y mover las imágenes al directorio de carga
    foreach ($imagenes['tmp_name'] as $key => $imagen_temporal) {
        $nombre_imagen = $imagenes['name'][$key];
        $ruta_imagen = "imagenes/articulos/" . $nombre_imagen;

        if ($imagenes['error'][$key] === UPLOAD_ERR_OK) {
            $tipoImagen = $imagenes['type'][$key];
            if ($tipoImagen == 'image/jpeg' || $tipoImagen == 'image/png' || $tipoImagen == 'image/gif') {
                // Mover el archivo al directorio de carga
                if (move_uploaded_file($imagen_temporal, $ruta_imagen)) {
                    $rutaImagenes[] = $ruta_imagen; // Guardar la ruta de la imagen
                } else {
                    echo "Error al mover el archivo de imagen $nombre_imagen.";
                    exit();
                }
            } else {
                echo "Error: La imagen $nombre_imagen debe ser JPEG, PNG o GIF.";
                exit();
            }
        } else {
            echo "Error al subir la imagen $nombre_imagen.";
            exit();
        }
    }

    // Insertar el artículo en la tabla "articulos" con la ruta de la primera imagen
    if (!empty($rutaImagenes)) {
        $imagen_principal = $rutaImagenes[0]; // Tomar la primera imagen como imagen principal
        $stmt = $conn->prepare("INSERT INTO articulos (codigo, nombre, descripcion, precio, imagen, descuento, activo) 
                                VALUES (:codigo, :nombre, :descripcion, :precio, :imagen, :descuento, :activo)");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':imagen', $imagen_principal); // Almacenar solo la ruta de la imagen principal en la base de datos
        $stmt->bindParam(':descuento', $descuento);
        $stmt->bindParam(':activo', $activo);

        if ($stmt->execute()) {
            // Obtener el ID del artículo recién insertado
            $articulo_id = $conn->lastInsertId();

            // Insertar las relaciones entre el artículo y las categorías seleccionadas
            foreach ($subcategorias as $categoria) {
                $stmtRelacion = $conn->prepare("INSERT INTO articulos_categorias (articulo_codigo, categoria_codigo) 
                                                VALUES (:articulo_codigo, :categoria_codigo)");
                $stmtRelacion->bindParam(':articulo_codigo', $codigo);
                $stmtRelacion->bindParam(':categoria_codigo', $categoria);
                $stmtRelacion->execute();
            }

            // Insertar las rutas de las imágenes adicionales en la tabla "imagenes_articulos"
            foreach ($rutaImagenes as $ruta_imagen) {
                $stmtImagenes = $conn->prepare("INSERT INTO imagenes_articulos (articulo_codigo, ruta_imagen) VALUES (?, ?)");
                $stmtImagenes->bindParam(1, $codigo);
                $stmtImagenes->bindParam(2, $ruta_imagen);
                $stmtImagenes->execute();
            }

            echo "Artículo creado exitosamente.";
            echo '<a href="index.php"><button>Volver al inicio</button></a>';
            exit();
        } else {
            echo "Error al crear el artículo.";
        }
    } else {
        echo "Error: No se han subido imágenes.";
    }
} else {
    echo "Acceso no autorizado.";
}
?>

