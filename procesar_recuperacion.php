<link rel="stylesheet" href="styles.css">
<?php
require_once "conectar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST["dni"];
    $email = $_POST["email"];

    // Limpiar y validar los datos del formulario (puedes implementar esto según tus necesidades)

    // Verificar si el DNI y el correo electrónico coinciden
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE dni = :dni AND email = :email");
    $stmt->bindParam(":dni", $dni);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Guardar el ID del usuario en una variable de sesión para usarlo en la página de cambio de contraseña
        session_start();
        $_SESSION["usuario_id"] = $usuario["id"];

        // Redirigir a la página para cambiar la contraseña
        header("Location: cambiar_contraseña.php");
        exit();
    } else {
        // Mostrar un mensaje de error descriptivo
        echo "DNI y/o correo electrónico no válidos.";

        // Ofrecer la opción de volver al formulario de recuperación de contraseña
        ?>
        <button>
            <a href="recuperar_contraseña.php">Volver</a>
        </button>
        <?php
    }
}
?>
