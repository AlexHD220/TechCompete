<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Equipo | Detalles</title>
</x-plantilla-head>

<x-plantilla-body>

    <div class="d-flex justify-content-between" style="margin-bottom: 10px;">
        <h2 style="display: inline;"> {{ $equipo -> nombre }}</h2>
        @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
            @can('only-user')
                <button class="btn btn-primary" onclick="window.location.href = '/participante/create';">Registrar nuevo participante</button>
            @endcan
        @endauth
    </div>

    <div style="display: flex; ">
        <h4 style="margin-right: 15px;">Asesor:</h4>
        <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('asesor.show', $equipo->asesor)}}" style="text-decoration: none; color: inherit; display: inline-block;">
            <p style="font-size: 18px; margin-bottom: 15px;">{{ $equipo->asesor->nombre }}</p>
        </a>
    </div>

    <div style="display: flex;">
        <h4 style="margin-right: 15px;"> Competencia: </h4>

        @if($equipo -> competencia)
            <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('competencia.show', $equipo->competencia)}}" style="text-decoration: none; color: inherit;">
                <p style="font-size: 18px; margin-right: 8px;"> {{ $equipo->competencia->identificador }} </p>
            </a>
            <p style="font-size: 18px; margin-bottom: 15px;"> ({{ date('d/m/Y', strtotime($equipo->competencia->fecha)) }}) </p>
        @else
            <p style="font-size: 18px; margin-right: 8px;"> Esta competencia fue temporalmente deshabilitada </p>
        @endif
    </div>

    @if($equipo -> competencia)
        <div style="display: flex;">
            <h4 style="margin-right: 15px;"> Categoria: </h4>
            <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('categoria.show', $equipo->categoria)}}" style="text-decoration: none; color: inherit;">
                <p style="font-size: 18px; margin-bottom: 15px;"> {{ $equipo->categoria->nombre }} </p>
            </a>
        </div>
    @else
        <div style="margin-top: 10px;">
            <form action="{{route('equipo.destroy', $equipo)}}" method = "POST" style="display: inline-block;">
                @csrf
                @method('DELETE')

                <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar de forma permanente este equipo?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  style="background-color: red; color: white;">
                    Eliminar equipo
                </button>
            </form>
        </div>
    @endif

    @can('only-user')
        <div style="margin-top: 25px;">
            <a href="/equipo">Regresar</a> 
        </div>
    @endcan

</x-plantilla-body>

</html>