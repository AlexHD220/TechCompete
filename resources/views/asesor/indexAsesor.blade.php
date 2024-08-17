<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Asesores</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
            <h1 style="display: inline;">Listado de Asesores</h1>
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" onclick="window.location.href = '/asesor/create';">Registrar nuevo asesor</button>
            @endauth
        </div>

        @if ($asesores->count() == 0)
            <p style="margin-left: 20px;"><i>Aún no hay ningún asesor registrado.</i></p>
        @endif


        @foreach ($asesores as $asesor) <!--Listar todos los datos de la tabla asesores-->

            <li>
                <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('asesor.show', $asesor)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                    <b style="font-size: 20px;">{{ $asesor -> nombre }}</b>
                </a>
                |
                <a href="{{route('asesor.edit', $asesor)}}" style="display: inline-block;">
                    Editar
                </a>
                <!--|

                @can('delete', $asesor)
                    <form action="{{route('asesor.destroy', $asesor)}}" method = "POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')

                        <button type = "submit">Eliminar </button>
                    </form>
                @endcan-->
            </li><br>
        @endforeach

        <!--<br>
        <button onclick="window.location.href = '/asesor/create';">Registrar nuevo asesor</button>-->
    </div>
</x-plantilla-body>

</html>