<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Categorías</title>
</x-plantilla-head>

<x-plantilla-body>

    <div>
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
            <h1 style="display: inline;">Listado de Categorías</h1>
            
            <button class="btn btn-primary" onclick="window.location.href = '/categoria/create';">Registrar nueva categoría</button>
        </div>

        @if ($categorias->count() == 0)
            <p style="margin-left: 20px;"><i>Aún no hay ninguna categoría registrada.</i></p>
        @endif

        @foreach ($categorias as $categoria) <!--Listar todos los datos de la tabla categorias -->

            <li style="display: inline-block; margin-bottom: 5px;">
                <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('categoria.show', $categoria)}}" style="text-decoration: none; color: inherit;">
                    <b style="font-size: 20px;"> {{ $categoria -> nombre }} </b>
                </a>

                |
                <a href="{{route('categoria.edit', $categoria)}}">
                    Editar
                </a>
            </li>
            <p style="text-align: justify; max-width: 80%;">{{ $categoria -> descripcion}}</p>
            <br>
        @endforeach
    </div>
</x-plantilla-body>

</html>