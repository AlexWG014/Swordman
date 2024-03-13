<?php
session_start();
include 'conectar.php';

$fila = [];

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el ID del usuario a editar
    $id_actualizar = $_POST["id"];
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id_actualizar]);

    if ($stmt->rowCount() > 0) {
        $fila = $stmt->fetch();

        // Obtener los nuevos datos del formulario
        $dni = isset($_POST['dni']) ? $_POST['dni'] : $fila["dni"];
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : $fila["nombre"];
        $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : $fila["apellidos"];
        $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : $fila["direccion"];
        $localidad = isset($_POST['localidad']) ? $_POST['localidad'] : $fila["localidad"];
        $provincia = isset($_POST['provincia']) ? $_POST['provincia'] : $fila["provincia"];
        $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : $fila["telefono"];
        $email = isset($_POST['email']) ? $_POST['email'] : $fila["email"];
        $rol = isset($_POST['rol']) ? $_POST['rol'] : $fila["rol"];

        // Validar los datos
        if (empty($dni) || empty($nombre) || empty($apellidos) || empty($direccion) || empty($localidad) || empty($provincia) || empty($telefono) || empty($email)) {
            echo "Error: Todos los campos son obligatorios.";
        } elseif (!is_numeric($telefono)) {
            echo "Error: El DNI y el teléfono deben ser valores numéricos.";
        } else {
            // Verificar si el DNI o el nombre ya existen en la base de datos
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE (dni = ? OR nombre = ?) AND id != ?");
            $stmt->execute([$dni, $nombre, $id_actualizar]);
            if ($stmt->rowCount() > 0) {
                echo "Error: El DNI o el nombre ya existen en la base de datos.";
            } else {
                // Actualizar los datos del usuario en la base de datos
                $stmt = $conn->prepare("UPDATE usuarios SET dni=?, nombre=?, apellidos=?, direccion=?, localidad=?, provincia=?, telefono=?, email=?, rol=?  WHERE id=?");
                $stmt->execute([$dni, $nombre, $apellidos, $direccion, $localidad, $provincia, $telefono, $email,  $rol, $id_actualizar]);

                // Verificar si se actualizó correctamente
                if ($stmt->rowCount() > 0) {
                    echo "¡Los datos se han actualizado correctamente!";
                    // Recuperar los datos actualizados del usuario
                    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
                    $stmt->execute([$id_actualizar]);
                    $fila = $stmt->fetch();
                } else {
                    echo "Error al actualizar los datos.";
                }
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
    <style>
                .container {
            width: 400px;
            margin: 0 auto;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            margin-left: 150px;
            display: flex;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
        }

        .error-message {
            color: red;
            margin-top: 5px;
        }
    </style>
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

            <?php if ($_SESSION['user']['rol'] == "administrador") : ?>
            <label for="rol">Rol:</label>
            <select id="rol" name="rol">
                <option value="administrador" <?php if(isset($fila["rol"]) && $fila["rol"] == "administrador") echo "selected"; ?>>Administrador</option>
                <option value="cliente" <?php if(isset($fila["cliente"]) && $fila["rol"] == "cliente") echo "selected"; ?>>Cliente</option>
                <option value="empleado" <?php if(isset($fila["rol"]) && $fila["rol"] == "empleado") echo "selected"; ?>>Empleado</option>
            </select><br>
            <?php endif; ?>

            <input type="hidden" id="id" name="id" value="<?php echo isset($fila["id"]) ? $fila["id"] : ''; ?>">

            <button id="actualizarBtn" type="submit">Actualizar</button>
        </form>
        <p>Prefieres volver? <a href="consulta.php">Volver a la consulta</a></p>
    </div>
</body>
</html>
