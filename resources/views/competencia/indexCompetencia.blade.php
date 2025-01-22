<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Competencias</title>

    <style>
        .competencias-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Dos columnas por defecto */
            /*gap: 25px;*/ /* Espaciado entre columnas y filas */
            row-gap: 30px;   /* Espaciado entre filas */
            column-gap: 25px; /* Espaciado entre columnas */
            
            
            justify-items: center; /* Centra horizontalmente */
            align-items: center; /* Centra verticalmente */
        }

        /* Pantallas intermedias: tabletas o celulares horizontales */
        /*@media (max-width: 1024px) {*/
        @media (max-width: 991.98px) {
            .competencias-container {
                grid-template-columns: repeat(2, 1fr); /* Cambiar a dos columnas por fila */
            }
        }

        /* Pantallas pequeñas: celulares en orientación vertical */
        /*@media (max-width: 768px) {*/
        @media (max-width: 650px) {
            .competencias-container {
                grid-template-columns: 1fr; /* Cambiar a una columna por fila */
            }
        }

        .sombra {
            /*box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.5);*/
            box-shadow: 0px 0px 5px 3px rgba(255,255,255,0.2);
        }
    </style>
</x-plantilla-head>

<x-plantilla-body>

    @if (session('alerta'))
        <script>                
            document.addEventListener('DOMContentLoaded', function () {            
                    // Captura los datos de la sesión y llama a la función                        
                    sweetAlertNotification("{{ session('alerta.titulo') }}", "{{ session('alerta.texto') }}", "{{ session('alerta.icono') }}", "{{ session('alerta.tiempo') }}", "{{ session('alerta.botonConfirmacion') }}");
            });
        </script>
    @endif

<!--@php
$timestampNow = now()->toDateString();
@endphp
<p>Timestamp actual: {{ $timestampNow }}</p>-->

<div>

    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
        <h1 style="display: inline;">Listado de Competencias</h1>   
        @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
            @can('only-superadmin')                
                <button class="btn btn-primary" onclick="window.location.href = '/competencia/draft';">Borradores</button>                
            @endcan
        @endauth
    </div>

    <!--<div class="d-flex justify-content-between align-items-center" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;"> -->    
    @if($pasadascount > 0)            
        <div class="d-flex align-items-center" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 20px;">
            <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
            onclick="window.location.href = '/competencia/previous';"><b>Competencias Pasadas</b></button>                    

            <!--@auth 
                @can('only-superadmin')   
                    @if(0)                
                        <button onMouseOver="this.style.backgroundColor='#bd4e00'" onmouseout="this.style.backgroundColor='#e26b18'" class="btn btn-primary" style="font-size: 14px; background-color: #e26b18; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
                        onclick="window.location.href = '/competencia/trashed';"><b>Competencias Eliminadas</b></button>                    
                    @endif
                @endcan
            @endauth-->
        </div>
    @endif

    @if ($competencias->count() == 0 && $actuales->count() == 0)
        <p style="margin-left: 20px;"><i>No hay ninguna competencia disponible actualmente.</i></p>
    @endif

    @if ($actuales->count() > 0)
        <h3>Competencias en progreso.</h3>

        <!-- Contenedor con grid -->
        <div class="competencias-container" style="margin-top: 15px; margin-bottom: 35px;">
            @foreach ($actuales as $actual)            
                    <!--<div class="competencia-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;">-->
                    <div class="competencia-card">
                        <!-- Imagen -->
                        <div class="text-center"> 
                            <a href="{{ route('competencia.show', $actual) }}" style="text-decoration: none; color: inherit;">
                                <img class="sombra" src="{{ \Storage::url($actual->ubicacion_imagen) }}" alt="Logo competencia" style="width: 100%; max-height:px; object-fit: cover; border-radius: 10px;">
                            </a>
                        </div>

                        <div class="text-center" style="margin-top: 10px;">
                            <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{ route('competencia.show', $actual) }}" style="text-decoration: none; color: inherit;">
                                <b style="font-size: 25px;">{{ $actual->name }}</b>
                            </a>
                        </div>

                        <div class="text-center" style="margin-top: 0px;">
                            <a href="{{ $actual->mapa_link }}" style="text-decoration: none;" target="_blank" rel="noopener noreferrer" title="Ubicación">
                                <h style="font-size: 15px;">{{ $actual->ubicacion }}</h>
                            </a>
                        </div>

                        <div class="text-center" style="margin-top: 0px;">                            
                            <h style="font-size: 15px;">{{ $actual->sede }}</h>                            
                        </div>

                        <!--<div class="text-center" style="margin-top: 5px; font-size: 16px;">
                            <b>Fecha:</b> {{ date('d/m/Y', strtotime($actual->fecha)) }} al {{ date('d/m/Y', strtotime($actual->fecha_fin)) }}
                        </div>-->

                        @if($actual->duracion == 1)
                            <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                {{ \Carbon\Carbon::parse($actual->fecha)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}                                
                            </div> 
                        @elseif ($actual->duracion == 2)
                            @if (\Carbon\Carbon::parse($actual->fecha)->month == \Carbon\Carbon::parse($actual->fecha_fin)->month)
                                <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                    {{ \Carbon\Carbon::parse($actual->fecha)->locale('es')->isoFormat('D') }} y {{ \Carbon\Carbon::parse($actual->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}                                
                                </div> 
                            @else
                                <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                    {{ \Carbon\Carbon::parse($actual->fecha)->locale('es')->isoFormat('D [de] MMMM') }} y {{ \Carbon\Carbon::parse($actual->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                                </div> 
                            @endif
                        @elseif (\Carbon\Carbon::parse($actual->fecha)->year == \Carbon\Carbon::parse($actual->fecha_fin)->year)                             
                            @if (\Carbon\Carbon::parse($actual->fecha)->month == \Carbon\Carbon::parse($actual->fecha_fin)->month)
                                <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                    {{ \Carbon\Carbon::parse($actual->fecha)->locale('es')->isoFormat('D') }} al {{ \Carbon\Carbon::parse($actual->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}                                
                                </div> 
                            @else
                                <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                    {{ \Carbon\Carbon::parse($actual->fecha)->locale('es')->isoFormat('D [de] MMMM') }} al {{ \Carbon\Carbon::parse($actual->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                                </div> 
                            @endif                                
                        @else
                            <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                {{ \Carbon\Carbon::parse($actual->fecha)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }} <br> al {{ \Carbon\Carbon::parse($actual->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}                                
                            </div>                        
                        @endif

                        @auth
                            @can('only-superadmin')

                                <div class="text-center" style="margin-top: 10px;">
                                    <!-- Botón para Editar -->
                                    <a href="{{ route('competencia.edit', $actual) }}" onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #434851; color: white; border: none; padding: 5px 10px; border-radius: 5px; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Editar Competencia">                                
                                        <i class="fas fa-edit" style="font-size: 18px; margin-right: 5px;"></i> <!-- Ícono de FontAwesome -->
                                        Editar
                                    </a>    
                                </div>
                            @endcan
                        @endauth
                    </div>
            @endforeach
        </div>
    @endif

    @if ($competencias->count() > 0)
        <h3>Próximas competencias.</h3>

        <!-- Contenedor con grid -->
        <div class="competencias-container" style="margin-top: 15px;  margin-bottom: 35px;">
            @foreach ($competencias as $competencia)            
                    <!--<div class="competencia-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;">-->
                    <div class="competencia-card">
                        <!-- Imagen -->
                        <div class="text-center"> 
                            <a href="{{ route('competencia.show', $competencia) }}" style="text-decoration: none; color: inherit;">
                                <img class="sombra" src="{{ \Storage::url($competencia->ubicacion_imagen) }}" alt="Logo competencia" style="width: 100%; max-height:px; object-fit: cover; border-radius: 10px;">
                            </a>
                        </div>

                        <div class="text-center" style="margin-top: 10px;">
                            <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{ route('competencia.show', $competencia) }}" style="text-decoration: none; color: inherit;">
                                <b style="font-size: 25px;">{{ $competencia->name }}</b>
                            </a>
                        </div>

                        <div class="text-center" style="margin-top: 0px;">
                            <a href="{{ $competencia->mapa_link }}" style="text-decoration: none;" target="_blank" rel="noopener noreferrer" title="Ubicación">
                                <h style="font-size: 15px;">{{ $competencia->ubicacion }}</h>
                            </a>
                        </div>

                        <div class="text-center" style="margin-top: 0px;">                            
                            <h style="font-size: 15px;">{{ $competencia->sede }}</h>                            
                        </div>

                        <!--<div class="text-center" style="margin-top: 5px; font-size: 16px;">
                            <b>Fecha:</b> {{ date('d/m/Y', strtotime($competencia->fecha)) }}                        
                        </div>-->

                        <div class="text-center" style="margin-top: 5px; font-size: 16px;">                            
                            <b>Fecha:</b> {{ \Carbon\Carbon::parse($competencia->fecha)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                        </div>

                        @auth
                            @can('only-superadmin')

                                <div class="text-center" style="margin-top: 10px;">
                                    <!-- Botón para Editar -->
                                    <a href="{{ route('competencia.edit', $competencia) }}" onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #434851; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Editar Competencia">                                
                                        <i class="fas fa-edit" style="font-size: 20px;"></i> <!-- Ícono de FontAwesome -->
                                    </a>    

                                    <form action="{{route('competencia.publicar', $competencia)}}" method = "POST" style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <button type="submit" onclick="return confirm('¿Está seguro que desea desactivar esta publicación?')" onmouseover="this.style.backgroundColor='#f97c3e';" onmouseout="this.style.backgroundColor='#ff5500';"  
                                        style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #ff5500; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                        title="Desctivar Publicación">
                                            <!--<i class="fas fa-ban" style="font-size: 20px;"></i>--> <!-- Ícono de FontAwesome -->
                                            <i class="fa-solid fa-right-to-bracket" style="font-size: 20px;"></i> <!-- Ícono de FontAwesome -->                                            
                                        </button>
                                    </form>
                                    
                                    <form action="{{route('competencia.destroy', $competencia)}}" method = "POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')

                                        <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">
                                        
                                        <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta competencia?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                        style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                        title="Eliminar Competencia">
                                            <i class="fas fa-trash" style="font-size: 20px;"></i> <!-- Ícono de FontAwesome -->
                                        </button>
                                    </form>   
                                </div>
                            @endcan
                        @endauth
                    </div>
            @endforeach
        </div>
    @endif

</div>
</x-plantilla-body>

</html>