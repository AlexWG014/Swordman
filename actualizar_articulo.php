<?php
include 'conectar.php';

// Verificar si se recibió una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibió un código de artículo
    if(isset($_POST['codigo'])) {
        $codigo = $_POST['codigo'];
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $descuento = $_POST['descuento'];
        $activo = isset($_POST['activo']) ? 1 : 0;

        // Preparar la consulta para actualizar el artículo
        $stmt = $conn->prepare("UPDATE articulos SET nombre = ?, descripcion = ?, precio = ?, descuento = ?, activo = ? WHERE codigo = ?");
        // Ejecutar la consulta
        $stmt->execute([$nombre, $descripcion, $precio, $descuento, $activo, $codigo]);

        // Redirigir a la página de consulta de artículos después de la actualización
        header("Location: consulta_articulos.php");
        exit(); // Detener la ejecución del script después de la redirección
    } else {
        echo "No se proporcionó un código de artículo.";
    }
} else {
    // Si no se recibió una solicitud POST, redirigir a la página de inicio
    header("Location: index.php");
    exit(); // Detener la ejecución del script después de la redirección
}
?>
