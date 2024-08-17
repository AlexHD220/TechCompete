<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Listado de usuarios</h1>
    @foreach ($usuarios as $usuario) <!--Listar todos los datos de la tabla usuarios-->
        <!-- <li>{{$usuario->usuario}}</li> --> <!--Nombre de columna (listar datos de cada columna)-->
        <!-- <li>{{$usuario->mail}}</li> -->

        <li>
            <a href="{{route('usuario.show', $usuario)}}">
                {{ $usuario -> usuario }}
            </a>
                | 
            <a href="{{route('usuario.edit', $usuario)}}">
                Editar
            </a>
        </li>
        <br>
    @endforeach
</body>
</html>