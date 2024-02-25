<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<?php
session_start();
include 'conectar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el ID del usuario a editar
    $id_actualizar = $_POST["id"];
    $stmt_select = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt_select->execute([$id_actualizar]);

    if ($stmt_select->rowCount() > 0) {
        $fila = $stmt_select->fetch();

        // Obtener los nuevos datos del formulario
        $dni = isset($_POST['dni']) ? $_POST['dni'] : $fila["dni"];
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : $fila["nombre"];
        $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : $fila["apellidos"];
        $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : $fila["direccion"];
        $localidad = isset($_POST['localidad']) ? $_POST['localidad'] : $fila["localidad"];
        $provincia = isset($_POST['provincia']) ? $_POST['provincia'] : $fila["provincia"];
        $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : $fila["telefono"];
        $email = isset($_POST['email']) ? $_POST['email'] : $fila["email"];
        $password = isset($_POST['password']) ? $_POST['password'] : $fila["password"];

        // Validar los datos
        if (empty($dni) || empty($nombre) || empty($apellidos) || empty($direccion) || empty($localidad) || empty($provincia) || empty($telefono) || empty($email)) {
            echo "Error: Todos los campos son obligatorios.";
        } elseif (!is_numeric($telefono)) {
            echo "Error: El DNI y el teléfono deben ser valores numéricos.";
        } else {
            // Actualizar los datos del usuario en la base de datos
            $stmt_update = $conn->prepare("UPDATE usuarios SET dni=?, nombre=?, apellidos=?, direccion=?, localidad=?, provincia=?, telefono=?, email=? WHERE id=?");
            $resultado = $stmt_update->execute([$dni, $nombre, $apellidos, $direccion, $localidad, $provincia, $telefono, $email, $id_actualizar]);

            if ($resultado) {
                echo "¡Se han cambiado los datos exitosamente!";
                echo " Recargue la pagina para poder visualizarlos";
            } else {
                echo "Error en el cambio de datos del usuario.";
            }

            // Solo actualizar la contraseña si se proporciona una nueva
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt_password = $conn->prepare("UPDATE usuarios SET password=? WHERE id=?");
                $stmt_password->execute([$hashed_password, $id_actualizar]);
            }
        }
    } else {
        echo "Error: Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <h2>Editar Usuario</h2>
        <form method="post" action="editar_usuario.php">
            
        <label for="dni">DNI:</label>
        <input type="text" value="<?php echo isset($fila["dni"]) ? $fila["dni"] : ''; ?>" name="dni" required><br>
        
        <label for="nombre">Nombre:</label>
        <input type="text" value="<?php echo isset($fila["nombre"]) ? $fila["nombre"] : ''; ?>" name="nombre" required><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" value="<?php echo isset($fila["apellidos"]) ? $fila["apellidos"] : ''; ?>" name="apellidos" required><br>
        
        <label for="direccion">Dirección:</label>
        <input type="text" value="<?php echo isset($fila["direccion"]) ? $fila["direccion"] : ''; ?>" name="direccion" required><br>
        
        <label for="localidad">Localidad:</label>
        <input type="text" value="<?php echo isset($fila["localidad"]) ? $fila["localidad"] : ''; ?>" name="localidad" required><br>
        
        <label for="provincia">Provincia:</label>
        <input type="text" value="<?php echo isset($fila["provincia"]) ? $fila["provincia"] : ''; ?>" name="provincia" required><br>
        
        <label for="telefono">Teléfono:</label>
        <input type="text" value="<?php echo isset($fila["telefono"]) ? $fila["telefono"] : ''; ?>" name="telefono" required><br>
        
        <label for="email">Email:</label>
        <input type="email" value="<?php echo isset($fila["email"]) ? $fila["email"] : ''; ?>" name="email" required><br>

        <label for="password">Nueva Contraseña:</label>
        <a href="recuperar_contraseña.php">Cambiar</a>

        <input type="hidden" name="id" value="<?php echo isset($fila["id"]) ? $fila["id"] : ''; ?>">
            
                <input type="submit" value="Actualizar">
        </form>

        <p>Prefieres volver? <a href="consulta.php">Volver a la consulta</a></p>
    </div>
</body>
</html>
<?php
// Deshabilitar la caché
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>
