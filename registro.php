<?php
include 'conectar.php';

// Inicializa el array de mensajes de error
$error_messages = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $direccion = $_POST['direccion'];
    $localidad = $_POST['localidad'];
    $provincia = $_POST['provincia'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar el formato del número de teléfono
    if (!preg_match("/^[0-9]{9}$/", $telefono)) {
        $error_messages[] = "Error: El formato del número de teléfono no es válido.";
    }

    // Verificar si el DNI ya está registrado
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE dni = :dni");
    $stmt->bindParam(':dni', $dni);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error_messages[] = "Error: El DNI ya está registrado.";
    }

    if (!preg_match("/^[0-9]{8}[A-Za-z]$/", $dni)) {
        $error_messages[] = "Error: El formato del DNI no es válido.";
    }

    // Verificar si el correo electrónico ya está registrado
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error_messages[] = "Error: El correo electrónico ya está registrado.";
    }

    // Verificar si el nombre ya está registrado
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = :nombre");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error_messages[] = "Error: El nombre de usuario ya está registrado.";
    }

    // Si no hay mensajes de error, continuar con la inserción
    if (empty($error_messages)) {
        try {
            // Continuar con la inserción en la base de datos
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO usuarios (dni, nombre, apellidos, direccion, localidad, provincia, telefono, email, password) VALUES (:dni, :nombre, :apellidos, :direccion, :localidad, :provincia, :telefono, :email, :password)");
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':localidad', $localidad);
            $stmt->bindParam(':provincia', $provincia);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            $stmt->execute();

            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            $error_messages[] = "Error al registrar el usuario: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <style>
        #registro {
            margin-left: 250px;
            width: 100%; /* Puedes ajustar este valor según tus preferencias */
            max-width: 8000px; /* Limita el ancho máximo para mejorar la legibilidad en pantallas anchas */
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div id="container">
        <?php include 'left-menu.php'; ?>
        <div id="main-content">
            <!-- Contenedor de mensajes de error -->
            <div id="error-container" style="text-align: center; margin-bottom: 20px;">
                <?php if (!empty($error_messages)) : ?>
                    <div style="color: red;">
                        <?php foreach ($error_messages as $error_message) : ?>
                            <p><?php echo $error_message; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <h2>Registro</h2>

            <section id="registro">
                <form method="post" action="registro.php">
                    <label for="dni">DNI:</label>
                    <input type="text" name="dni" required><br>
                    
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" required><br>

                    <label for="apellidos">Apellidos:</label>
                    <input type="text" name="apellidos" required><br>
                    
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" required><br>
                    
                    <label for="localidad">Localidad:</label>
                    <input type="text" name="localidad" required><br>
                    
                    <label for="provincia">Provincia:</label>
                    <input type="text" name="provincia" required><br>
                    
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" required pattern="[0-9]{9}" title="Debe contener 9 números" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)"><br>
                    
                    <label for="email">Email:</label>
                    <input type="email" name="email" required><br>

                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" required><br>

                    <input type="submit" value="Registrarse">
                    <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a></p>
                </form>
            </section>
        </div>
        <?php include 'right-panel.php'; ?>
    </div>
</body>
</html>
