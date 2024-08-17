<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Competencias</title>
</x-plantilla-head>

<x-plantilla-body>

<!--@php
$timestampNow = now()->toDateString();
@endphp
<p>Timestamp actual: {{ $timestampNow }}</p>-->

    <div>
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
            <h1 style="display: inline;">Listado de Competencias</h1>
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                @can('only-admin')
                    @if ($categorias->count() > 0)
                        <button class="btn btn-primary" onclick="window.location.href = '/competencia/create';">Registrar nueva competencia</button>
                    @else
                        <div style="text-align: center;">
                            <a href="/categoria" style="font-size: 14px;"><i>Para registrar una nueva competencia,<br>
                                                                             primero agrega sus categorías.</i></a>
                        </div>                        
                    @endif
                @endcan
            @endauth
        </div>

        @if ($competencias->count() == 0)
            @can('only-admin')
                <p style="margin-left: 20px;"><i>Aún no hay ninguna competencia registrada.</i></p>
            @else
                <p style="margin-left: 20px;"><i>No hay nuevas competencias disponibles.</i></p>
            @endcan
        @endif


        @foreach ($competencias as $competencia) <!--Listar todos los competencias de la tabla competencias-->
            @if (strtotime($competencia->fecha) >= strtotime(now()->toDateString()))
                
                <div class="flex justify-center">
                    <img src="{{ \Storage::url($competencia->ubicacion_imagen) }}" alt="Logo competencia" style="width: 500px;">
                </div>
            
                <div class="table-responsive flex justify-center" style="margin-bottom: 10px;">
                    <!--<table border="1" class="table text-start align-middle table-bordered table-hover mb-0" style="width: 90%; margin: 0 auto;">-->
                        <!--<thead>
                        <tr class="text-white"  style="border-bottom: 2px solid white; text-align: center;">
                            <th>Identificador</th>
                            <th>Asesor</th>
                            <th>Fecha de inicio</th>
                            <th>Fecha de fin</th>
                            <th>Duración</th>
                        </tr>
                    </thead>-->

                    <table class="table text-start align-middle table-bordered table-hover mb-0" style="width: auto; border-bottom: 2px solid white;">                
                        <tbody>
                            <tr style="text-align: center;">
                                <td style="padding-left: 20px; padding-right: 20px;"><a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('competencia.show', $competencia)}}" style="text-decoration: none; color: inherit;">
                                    <b style="font-size: 22px;"> - {{ $competencia->identificador }} - </b>
                                </a></td>
                                <!--{{--<td>{{ $competencia -> asesor -> nombre }}</td>--}}-->
                                <!--<td>{{ date('d/m/Y', strtotime($competencia->fecha)) }}</td>--> <!--$competencia->fecha -->
                                <!--<td>{{ date('d/m/Y', strtotime($competencia->fecha . '+' . $competencia->duracion . 'days')) }}</td>
                                <td>{{ $competencia->duracion }} días</td>-->

                                @auth
                                    @can('only-admin')
                                        <td style="padding-left: 20px; padding-right: 20px;">
                                            <a href="{{route('competencia.edit', $competencia)}}">
                                                Editar
                                            </a>
                                        </td>

                                        <td style="padding-left: 20px; padding-right: 20px;">
                                            <form action="{{route('competencia.destroy', $competencia)}}" method = "POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar la publicación de esta competencia?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  style="background-color: red; color: white;">
                                                    Eliminar 
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                @endauth
                            </tr>                            
                        
                        </tbody>
                    </table><br>
                </div>

                <div class="flex justify-center">
                    {{ date('d/m/Y', strtotime($competencia->fecha)) }} <!--$competencia->fecha -->
                </div><br><br>
            @endif
        @endforeach

        <!--<br>
        <button onclick="window.location.href = '/competencia/create';">Registrar nueva competencia</button>-->
    </div>
</x-plantilla-body>

</html>