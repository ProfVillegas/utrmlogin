<?php
session_start();
var_dump($_SESSION);
session_destroy();
header('location:../index.php?msg=logout');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrar sesión</title>
</head>

<body>
    <a href="../index.php">Inicio</a>
</body>

</html>