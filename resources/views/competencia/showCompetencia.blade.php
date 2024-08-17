<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Competencia | Mostrar</title>
</x-plantilla-head>

<x-plantilla-body>

    <h1> {{ $competencia -> identificador }}</h1> <!--Mostrar detalles-->
    <h4> Duración: {{ $competencia->duracion }} días</h4>
    <h4> Inauguración: {{ date('d/m/Y', strtotime($competencia->fecha)) }} </h4>
    <h4> Cierre: {{ date('d/m/Y', strtotime($competencia->fecha . '+' . ($competencia->duracion)-1 . 'days')) }}</h4>


    <br>
    @if ($competencia->categorias->count() > 1)
        <h4>Categorías</h4>
    @else
        <h4>Categoría</h4>
    @endif

    <ul>
        @foreach($competencia->categorias as $categoria)
        <li>
            <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('categoria.show', $categoria)}}" style="text-decoration: none; color: inherit;">
                {{ $categoria -> nombre }}
            </a>
        </li>
        @endforeach
    </ul>


    @can('only-admin')
        @if ($competencia->tipo == 'Equipo' && $equipos->count() > 0)
            <br>
            <h4>Equipos registrados</h4>

            <ul>
                @foreach($equipos as $equipo)
                    <li>
                        <!--{{ $equipo -> nombre }}-->
                        <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('equipo.show', $equipo)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                            <b>{{ $equipo -> nombre }}</b>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($competencia->tipo == 'Proyecto' && $proyectos->count() > 0)
            <br>
            <h4>Proyectos registrados</h4>

            <ul>
                @foreach($proyectos as $proyecto)
                    <li>
                        <!--{{ $proyecto -> nombre }}-->
                        <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('proyecto.show', $proyecto)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                            <b>{{ $proyecto -> nombre }}</b>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    @endcan


    <div style="margin-top: 25px;">
        <a href="/competencia">Regresar</a> 
    </div>

</x-plantilla-body>

</html>