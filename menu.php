<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        #menu {
            background-color: #333;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #menu ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #menu ul li {
            display: inline-block;
            margin-right: 10px;
        }

        #menu ul li a {
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
        }

        #menu ul li a:hover {
            background-color: #555;
            transition: background-color 0.3s;
        }
    </style>
    <title>Tu Página</title>
</head>
<body>
    <div id="menu">
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="quienes_somos.php">Quiénes somos</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="envio.php">Envío</a></li>
        </ul>
    </div>
</body>
</html>
