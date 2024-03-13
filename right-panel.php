<div id="right-panel">
    <?php

    include 'conectar.php';

    // Verificar si hay un mensaje de bienvenida
    if (isset($_SESSION['login_message'])) {
        echo '<p style="color: green;">DEBUG: ' . $_SESSION['login_message'] . '</p>';
        unset($_SESSION['login_message']); // Limpiar el mensaje después de mostrarlo
    }

    // Verificar si el usuario está autenticado
    if (isset($_SESSION['user'])) {
        echo '<p>Bienvenido, ' . $_SESSION['user']['nombre'] . '</p>';
        echo '<a href="cerrar_sesion.php"><img src="imagenes/cerrarsesion.png" alt="Cerrar Sesion" width="50" height="50"></a>';
        echo '<a href="consulta.php"><img src="imagenes/editar.png" alt="Editar Usuario" width="50" height="50"></a>';
    } else {
        // Mostrar mensaje de error si existe
        if (isset($_GET['login_error']) && $_GET['login_error'] == 'true') {
            echo '<p style="color: red;">Error: Nombre de usuario o contraseña incorrectos.</p>';
        }
        ?>
        <!-- Formulario para iniciar sesión o registrarse -->
        <form action="comprobarlogin.php" method="post">
            <!-- Agrega campos del formulario según sea necesario -->
            <input type="text" name="username" placeholder="Usuario">
            <input type="password" name="password" placeholder="Contraseña">
            <input type="submit" value="Iniciar Sesión">
            <a href="registro.php" title="Registrarse">
                <img src="imagenes/registro.png" alt="Cesta" width="50" height="50">
                </a>
            <a href="recuperar_contraseña.php" title="Recuperar Contraseña">            
                <img src="imagenes/contraseña.png" alt="Cesta" width="50" height="50">
            </a>

        </form>
        <?php
    }
    ?>

            <div style="margin-bottom: 10px;"></div>
            <hr class="separator">
            <div style="margin-bottom: 10px;"></div>

    <?php
        // Calcular el total de artículos añadidos y el subtotal
    $total_articulos = 0;
    $subtotal = 0;
    $descuento = 10;

    if (!empty($_SESSION['cesta'])) {
        foreach ($_SESSION['cesta'] as $articulo) {
            $total_articulos += $articulo['cantidad'];
            $subtotal += $articulo['precio'] * $articulo['cantidad'] * (1 - $articulo['descuento'] / 100);
        }
    }
    ?>        
    <a href="cesta.php" title="Cesta">
    <img src="imagenes/cesta.png" alt="Cesta" width="50" height="50">
</a>

    <!-- Mostrar el total de artículos añadidos y el subtotal -->
    <p>Total de Artículos: <?php echo $total_articulos; ?></p>
    <p>Subtotal: <?php echo $subtotal; ?>€</p>

    <!-- Contenedor para el resumen de los artículos -->
    <div id="resumen-articulos" style="display: none;">
    </div>

</div>
