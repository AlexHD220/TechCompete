<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Proyectos</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
            <h1 style="display: inline;">Lista de Proyectos</h1>
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                @can('only-user')
                    @if ($competencias->count() > 0)
                        @if ($asesores->count() > 0)
                            <button class="btn btn-primary" onclick="window.location.href = '/proyecto/create';">Registrar nuevo Proyecto</button>
                        @else
                            <div style="text-align: center;">
                                <a href="/asesor" style="font-size: 14px;"><i>Para registrar un nuevo proyecto,<br>
                                                                              primero agrega el asesor asignado.</i></a>
                            </div> 
                        @endif
                    @else
                        <div style="text-align: center;">
                            <a href="/competencia" style="font-size: 14px;"><i>Ahora mismo no hay competencias disponibles<br>
                                                                                para registrar nuevos proyectos.</i></a>
                        </div>                        
                    @endif
                @endcan
            @endauth
        </div>

        @if ($proyectos->count() == 0)
            <p style="margin-left: 20px;"><i>Aún no hay ningún proyecto registrado.</i></p>
        @endif


        @foreach ($proyectos as $proyecto) <!--Listar todos los datos de la tabla Proyectos-->

            <li>
                <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('proyecto.show', $proyecto)}}" style="text-decoration: none; color: inherit; display: inline-block; margin-bottom: 5px;">
                    <b style="font-size: 20px;">{{ $proyecto -> nombre }}</b>
                </a>
                
                @if($proyecto -> competencia)
                    ({{ $proyecto -> competencia -> identificador }}) |
                @else
                    (Competencia deshabilitada) |
                @endif

                <a href="{{route('proyecto.edit', $proyecto)}}" style="display: inline-block;">
                    Editar
                </a>
                
                |
                <form action="{{route('proyecto.destroy', $proyecto)}}" method = "POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')

                    <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar de forma permanente el registro de este proyecto?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  style="background-color: red; color: white;">
                        Eliminar 
                    </button>
                </form>
            </li>
            <p style="text-align: justify; max-width: 80%; margin-left: 22px;">{{ $proyecto -> descripcion}}</p>
            <br>
        @endforeach
    </div>
</x-plantilla-body>

</html>