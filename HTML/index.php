<?php
require '../PHP/conf_sesion.php';

if (!isset($_SESSION['cliente'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Tenis, zapatos, calzado">
    <meta name="author" content="Jose Angel Lopez Rocha">
    <link href="../CSS/productos.css" rel="stylesheet">
    <title>Zapatería</title>
</head>
<body>
    <header>
        <h1 class="titulo">Bienvenidos</h1>
        <nav>
            <ul class="sidenav">
                <li><a href="index.php">Index</a></li>
                <li><a href="acerca.php">Acerca de nosotros</a></li>
                <li><a href="clientes.php">Clientes</a></li>
                <li><a href="stock.php">Stock</a></li>
                <li><a href="../PHP/logout.php">Cerrar sesión</a></li>

            </ul>
        </nav>
    </header>

<h2 class="titulo">Catálogo de Calzado</h2>
<div id="contenedor-productos" class="productos"></div>

<footer class="footer">
        <h4>Información</h4>
        <ul>
            <li><a href="mailto:lopezrochaangel30@gmail.com">Enviar correo</a></li>    
        </ul>    
        <p>
            <a href="https://jigsaw.w3.org/css-validator/check/referer">
                <img style="border:0;width:88px;height:31px" src="https://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!">
            </a>
        </p>
        <p>
            <a href="https://jigsaw.w3.org/css-validator/check/referer">
                <img style="border:0;width:88px;height:31px" src="https://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!">
            </a>
        </p>
</footer>

<script src="../js/productos.js"></script>

<?php
include '../PHP/conexion.php';

if (isset($_SESSION['cliente_nombre'], $_SESSION['cliente_rol'])) {
    $nombre = $_SESSION['cliente_nombre'];
    $rol = $_SESSION['cliente_rol'];
}
?>

<p>Tu nombre es: <strong><?= htmlspecialchars($nombre) ?></strong></p>
<p>Tu rol es: <strong><?= htmlspecialchars($rol) ?></strong></p>

</body>
</html>
