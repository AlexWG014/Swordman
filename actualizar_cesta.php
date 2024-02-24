<?php
session_start();

if (isset($_POST['codigo']) && isset($_POST['cantidad']) && isset($_POST['action'])) {
    $codigo = $_POST['codigo'];
    $cantidad = $_POST['cantidad'];
    $action = $_POST['action'];

    if ($action === 'update') {
        if ($cantidad > 0) {
            $_SESSION['cantidad'][$codigo] = $cantidad;
        } else {
            unset($_SESSION['cesta'][$codigo]);
            unset($_SESSION['cantidad'][$codigo]);
        }
    } elseif ($action === 'remove') {
        unset($_SESSION['cesta'][$codigo]);
        unset($_SESSION['cantidad'][$codigo]);
    }

    header('Location: cesta.php');
    exit;
} else {
    echo 'Error: No se proporcionaron datos suficientes.';
}
?>
