<?php
include 'conectar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit();
    } else {
        // Si la verificación de contraseña falla, puedes agregar mensajes de depuración
        // Esto puede ayudarte a identificar problemas con el proceso de hash y verificación
        echo "Contraseña ingresada: $password <br>";
        echo "Contraseña almacenada: {$user['password']} <br>";

        header('Location: index.php?login_error=true');
        exit();
    }
} //else if (!isset($_SESSION['user'])) {
    //header('Location: index.php');
    //exit();
//}
?>