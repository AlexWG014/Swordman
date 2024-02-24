<?php
session_start();

// Verificar si se ha enviado un código de artículo para eliminar
if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Eliminar el artículo de la cesta
    unset($_SESSION['cesta'][$codigo]);
}

// Redireccionar de vuelta a la página de la cesta
header("Location: cesta.php");
exit();
?>
