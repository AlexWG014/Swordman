<?php
// Verificar si no hay una sesión iniciada antes de iniciar una nueva
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos (ya deberías tenerla configurada)
require_once "conectar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el id del usuario de la sesión
    $usuario_id = isset($_POST["id"]) ? $_POST["id"] : null;

    if ($usuario_id !== null) {
        // Obtener la nueva contraseña y realizar el hash
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":id", $usuario_id);

        if ($stmt->execute()) {
            // Contraseña cambiada exitosamente
            $message = "Contraseña cambiada exitosamente.";
        } else {
            // Error al cambiar la contraseña
            $message = "Error al cambiar la contraseña.";
        }

        // Limpiar la variable de sesión
        unset($_SESSION["user"]["id"]);
    } else {
        // No se proporcionó el ID del usuario
        $message = "Error: No se proporcionó el ID del usuario.";
    }
} else {
    // Método de solicitud incorrecto
    $message = "Error: Método de solicitud incorrecto.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

    </style>
</head>
<body>
    <div class="container">
        <?php if(isset($message)) echo "<p>$message</p>"; ?>
        <button class="button">
            <a href="index.php">Volver</a>
        </button>
    </div>
</body>
</html>
