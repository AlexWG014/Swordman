
<link rel="stylesheet" href="stylesconsulta.css">

<?php
include("conectar.php");
include("comprobarlogin.php");

// Verificar el rol del usuario
$usuario_actual = $_SESSION['user'];
$rol = $usuario_actual['rol'];

// Procesar la búsqueda y filtrado
$DNI = $_GET['DNI'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$apellidos = $_GET['apellidos'] ?? '';
$rol_filter = $_GET['rol'] ?? '';

// Construir la consulta SQL
$sql = "SELECT * FROM usuarios WHERE 1";

// Limitar la consulta si el usuario es cliente
if ($rol == "cliente") {
    $sql .= " AND id = :user_id";
    $user_id = $usuario_actual['id'];
} else {
    if (!empty($DNI)) {
        $sql .= " AND DNI LIKE '%$DNI%'";
    }
    
    if (!empty($nombre)) {
        $sql .= " AND nombre LIKE '%$nombre%'";
    }
    
    if (!empty($apellidos)) {
        $sql .= " AND apellidos LIKE '%$apellidos%'";
    }
    
    if (!empty($rol_filter)) {
        $sql .= " AND rol = '$rol_filter'";
    }
}

// Ejecutar la consulta
$stmt = $conn->prepare($sql);

// Si el usuario es cliente, bindea el id del usuario a la consulta
if ($rol == "cliente") {
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
}


$stmt->execute();
?>


<?php include 'menu.php'; ?>
<div style="margin-bottom: 50px;"></div>
<?php
if ($_SESSION['user']['rol'] == "administrador" || $_SESSION['user']['rol'] == "empleado") {
    ?>
<!-- Formulario de búsqueda y filtrado -->
<form action="consulta.php" method="GET"
>   <label for="DNI">DNI:</label>
    <input type="text" id="DNI" name="DNI" value="<?php echo $DNI ?>">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre ?>">
    <label for="apellidos">Apellidos:</label>
    <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos ?>">
    <label for="rol">Rol:</label>
    <select id="rol" name="rol">
        <option value="" <?php if ($rol_filter == "") echo "selected"; ?>>Todos</option>
        <option value="administrador" <?php if ($rol_filter == "administrador") echo "selected"; ?>>Administrador</option>
        <option value="cliente" <?php if ($rol_filter == "cliente") echo "selected"; ?>>Cliente</option>
        <option value="empleado" <?php if ($rol_filter == "empleado") echo "selected"; ?>>Empleado</option>
    </select>
    <button type="submit">Buscar</button>
</form>
<div style="margin-bottom: 50px;"></div>
<?php
}
?>
<table border="1">
    <tr>
        <td>DNI</td>
        <td>Nombre</td>
        <td>Apellidos</td>
        <td>Direccion</td>
        <td>Localidad</td>
        <td>Provincia</td>
        <td>Teléfono</td>
        <td>Email</td>
        <td>Rol</td>
        <?php if ($rol == "administrador") : ?>
            <td>Editar</td>
            <td>Borrar</td>
        <?php endif; ?>
    </tr>

    <?php
    while ($fila = $stmt->fetch()) {
    ?>
        <tr>
            <td><?php echo $fila["dni"] ?></td>
            <td><?php echo $fila["nombre"] ?></td>
            <td><?php echo $fila["apellidos"] ?></td>
            <td><?php echo $fila["direccion"] ?></td>
            <td><?php echo $fila["localidad"] ?></td>
            <td><?php echo $fila["provincia"] ?></td>
            <td><?php echo $fila["telefono"] ?></td>
            <td><?php echo $fila["email"] ?></td>
            <td><?php echo $fila["rol"]?></td>
            <?php if ($rol == "administrador" || $usuario_actual['id'] == $fila['id']) : ?>
                <td>
                    <form action="editar_usuario.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $fila["id"] ?>">
                        <button type="submit"><img src="imagenes/editar.png" alt="Editar Usuario" width="50" height="50"></button>
                    </form>
                </td>
                <td>
                    <form action="borrarusuario.php" method="post">
                        <button type="submit" name="borrar_usuario"> <img src="imagenes/eliminar.png" alt="borrar_usuario"  width="50" height="50"></button>
                        <input type="hidden" name="usuario_id" value="<?php echo $fila["id"] ?>">
                    </form>
                </td>
            <?php endif; ?>
        </tr>
        
    <?php
    }
    ?>
    
</table>
<div style="margin-bottom: 20px;"></div>

<a href="mis_pedidos.php">
    <button title="Mis pedidos"><img src="imagenes/mis_pedidos.png" alt="Cesta" width="50" height="50"></button>
</a>

<?php
if ($_SESSION['user']['rol'] == "administrador" || $_SESSION['user']['rol'] == "empleado") {
    ?>
    <a href="crear_productos.php">
        <button title="Crear articulos"><img src="imagenes/crear.png" alt="Crear" width="50" height="50"></button>
    </a>

    <a href="crear_categorias.php">
        <button title="Crear categorías"><img src="imagenes/categorias.png" alt="Crear" width="50" height="50"></button>
    </a>

    <a href="registro.php">
        <button title="Registro"><img src="imagenes/registro.png" alt="Cesta" width="50" height="50"></button>
    </a>
    <a href="consulta_articulos.php">
        <button title="Consultar artículos"><img src="imagenes/articulos.png" alt="Cesta" width="50" height="50"></button>
    </a>
    <a href="consulta_pedidos.php">
        <button title="Consultar pedidos"><img src="imagenes/consulta_pedidos.png" alt="Cesta" width="50" height="50"></button>
    </a>
    <a href="consulta_categorias.php">
        <button title="Consultar categorias"><img src="imagenes/lineas.png" alt="Cesta" width="50" height="50"></button>
    </a>

<?php
}
?>
<div style="margin-bottom: 20px;"></div>

    <?php include 'footer.php'; 
?>
