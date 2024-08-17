<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Equipos</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
            <h1 style="display: inline;">Lista de Equipos</h1>
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                @can('only-user')
                    @if ($competencias->count() > 0)
                        @if ($asesores->count() > 0)
                            <button class="btn btn-primary" onclick="window.location.href = '/equipo/create';">Registrar nuevo Equipo</button>
                        @else
                            <div style="text-align: center;">
                                <a href="/asesor" style="font-size: 14px;"><i>Para registrar un nuevo equipo,<br>
                                                                                primero agrega el asesor asignado.</i></a>
                            </div> 
                        @endif
                    @else
                        <div style="text-align: center;">
                            <a href="/competencia" style="font-size: 14px;"><i>Ahora mismo no hay competencias disponibles<br>
                                                                                para registrar nuevos equipos.</i></a>
                        </div>                        
                    @endif
                @endcan
            @endauth
        </div>

        @if ($equipos->count() == 0)
            <p style="margin-left: 20px;"><i>Aún no hay ningún equipo registrado.</i></p>
        @endif


        @foreach ($equipos as $equipo) <!--Listar todos los datos de la tabla equipos-->

            <li>
                <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('equipo.show', $equipo)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                    <b style="font-size: 20px;">{{ $equipo -> nombre }}</b>
                </a>
                @if($equipo -> competencia)
                    ({{ $equipo -> competencia -> identificador }}) |
                @else
                    (Competencia deshabilitada) |
                @endif
                <a href="{{route('equipo.edit', $equipo)}}" style="display: inline-block;">
                    Editar
                </a>
                
                |
                <form action="{{route('equipo.destroy', $equipo)}}" method = "POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')

                    <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar de forma permanente este equipo?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  style="background-color: red; color: white;">
                        Eliminar 
                    </button>
                </form>
            </li>
            <br>
        @endforeach
    </div>
</x-plantilla-body>

</html>