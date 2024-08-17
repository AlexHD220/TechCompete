<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2> Nombre Usuario: {{ $usuario -> usuario }}</h2> <!--Mostrar detalles-->
    <h3> Correo del usuario: {{ $usuario -> mail }}</h3>
    <h3> Contraseña: {{ $usuario -> pass }}</h3>

    <a href="/usuario">Regresar</a> 
</body>
</html>