<?php
session_start();
include 'conectar.php'; // Incluir el archivo de conexión a la base de datos

// Verificar si se ha enviado el formulario de selección de envío
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['envio'])) {
    // Obtener el nombre de envío y método de pago seleccionados
    $nombre_envio = $_POST['envio'];
    $nombre_metodo_pago = $_POST['metodo_pago'];

    // Guardar las opciones marcadas en la sesión
    if (isset($_POST['envio'])) {
        $_SESSION['envio'] = $_POST['envio'];
    }

    if (isset($_POST['metodo_pago'])) {
        $_SESSION['metodo_pago'] = $_POST['metodo_pago'];
    }

    // Realizar cualquier operación adicional que necesites con los datos seleccionados
    
    // Redirigir a la página de confirmación o cualquier otra página necesaria
    header("Location: confirmacion.php");
    exit(); // Terminar el script después de la redirección
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tramitar Envío y Pago</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos adicionales */
        .envio-container {
            text-align: center;
            margin-bottom: 20px; /* Ajustar el margen entre el título y los elementos */
        }

        .envio-options {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .envio-option {
            border: 1px solid #ccc;
            padding: 10px;
            width: 200px;
            text-align: center;
        }

        .envio-option img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
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

<header>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>
</header>

<main>
    <div id="container">
    <?php include 'left-menu.php'; ?>
    <div id="main-content">

        <!-- Título de la página -->
        <div class="envio-container">
        <h1>Seleccionar Envío y Método de Pago</h1>
        </div>

        <!-- Formulario de selección de envío y método de pago -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Opciones de envío -->
            <div class="envio-options">
                <?php
                // Consultar las opciones de envío desde la base de datos
                $query = "SELECT * FROM envio";
                $stmt = $conn->query($query);

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="envio-option">';
                        echo '<img src="' . $row['imagen'] . '" alt="' . $row['nombre'] . '">';
                        echo '<p>' . $row['nombre'] . '</p>';
                        echo '<p>' . $row['tiempo'] . '</p>';
                        echo '<input type="radio" name="envio" value="' . $row['nombre'] . '" required>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No hay opciones de envío disponibles.</p>";
                }
                ?>
            </div>

            <div style="margin-bottom: 50px;"></div>
            <hr class="separator">
            <div style="margin-bottom: 50px;"></div>


            <!-- Opciones de pago -->
            <div class="envio-options">
            <?php
                // Consultar las opciones de pago desde la base de datos
                $query_pago = "SELECT * FROM pago";
                $stmt_pago = $conn->query($query_pago);

                if ($stmt_pago->rowCount() > 0) {
                    while ($row_pago = $stmt_pago->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="envio-option">';
                        echo '<h2>' . $row_pago['nombre'] . '</h2>';
                        echo '<img src="' . $row_pago['imagen'] . '" alt="' . $row_pago['nombre'] . '">';
                        echo '<input type="radio" name="metodo_pago" value="' . $row_pago['nombre'] . '" required>';
                        echo '</div>';

                    }
                } else {
                    echo "<p>No hay métodos de pago disponibles.</p>";
                }
                ?>
            </div>
            <div style="margin-bottom: 10px;"></div>

            <!-- Botón para enviar el formulario -->
            <button type="submit">Continuar</button>
        </form>
        </div>

        <?php include 'right-panel.php'; ?>

    </div>
    
</main>

<footer>
    <?php include 'footer.php'; ?>
</footer>

</body>
</html>
