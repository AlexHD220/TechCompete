<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Competencias Pasadas</title>

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


        .oculto {
            opacity: 0.5;
        }

    </style>
</x-plantilla-head>

<x-plantilla-body>

<!--@php
$timestampNow = now()->toDateString();
@endphp
<p>Timestamp actual: {{ $timestampNow }}</p>-->

<div>

    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
        <h1 style="display: inline;">Listado de Competencias Pasadas</h1>   
    </div>

    @if ($competencias->count() == 0)
        <p style="margin-left: 20px;"><i>Aún no se ha llevado a cabo ninguna competencia registrada.</i></p>
    @else

        <h2 style="margin-bottom: 20px;">Historial</h2>
        
        @foreach ($competencias as $year => $competenciasAnueales)
            <h3>{{ $year }}</h3> <!-- Encabezado del año -->                            
            
                <!-- Contenedor con grid -->
                <div class="competencias-container" style="margin-top: 15px; margin-bottom: 35px;">
                    @foreach ($competenciasAnueales as $competencia)
                            <!--<div class="competencia-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;">-->
                            <div class="competencia-card {{ $competencia->oculta ? 'oculto' : '' }}">
                                <!-- Imagen -->
                                <div class="text-center"> 
                                    <a href="{{ route('competencia.showprevious', $competencia) }}" style="text-decoration: none; color: inherit;">
                                        <img class="sombra" src="{{ \Storage::url($competencia->ubicacion_imagen) }}" alt="Logo competencia" style="width: 100%; max-height:px; object-fit: cover; border-radius: 10px;">
                                    </a>
                                </div>

                                <div class="text-center" style="margin-top: 10px;">
                                    <a href="{{ route('competencia.showprevious', $competencia) }}" style="text-decoration: none; color: inherit;">
                                        <b style="font-size: 25px;">{{ $competencia->name }}</b>
                                    </a>
                                </div>

                                <div class="text-center" style="margin-top: 0px;">
                                    <a href="{{ $competencia->mapa_link }}" style="text-decoration: none;" target="_blank" rel="noopener noreferrer" title="Sede">
                                        <h style="font-size: 15px;">{{ $competencia->sede }}</h>
                                    </a>
                                </div>

                                @if($competencia->duracion == 1)
                                    <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                        {{ \Carbon\Carbon::parse($competencia->fecha)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}                                
                                    </div> 
                                @elseif ($competencia->duracion == 2)
                                    @if (\Carbon\Carbon::parse($competencia->fecha)->month == \Carbon\Carbon::parse($competencia->fecha_fin)->month)
                                        <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                            {{ \Carbon\Carbon::parse($competencia->fecha)->locale('es')->isoFormat('D') }} y {{ \Carbon\Carbon::parse($competencia->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}                                
                                        </div> 
                                    @else
                                        <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                            {{ \Carbon\Carbon::parse($competencia->fecha)->locale('es')->isoFormat('D [de] MMMM') }} y {{ \Carbon\Carbon::parse($competencia->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                                        </div> 
                                    @endif
                                @elseif (\Carbon\Carbon::parse($competencia->fecha)->year == \Carbon\Carbon::parse($competencia->fecha_fin)->year)                             
                                    @if (\Carbon\Carbon::parse($competencia->fecha)->month == \Carbon\Carbon::parse($competencia->fecha_fin)->month)
                                        <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                            {{ \Carbon\Carbon::parse($competencia->fecha)->locale('es')->isoFormat('D') }} al {{ \Carbon\Carbon::parse($competencia->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}                                
                                        </div> 
                                    @else
                                        <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                            {{ \Carbon\Carbon::parse($competencia->fecha)->locale('es')->isoFormat('D [de] MMMM') }} al {{ \Carbon\Carbon::parse($competencia->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                                        </div> 
                                    @endif                                
                                @else
                                    <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                                        {{ \Carbon\Carbon::parse($competencia->fecha)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }} <br> al {{ \Carbon\Carbon::parse($competencia->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}                                
                                    </div>                        
                                @endif

                                @auth
                                    @can('only-superadmin')

                                        <div class="text-center" style="margin-top: 10px;">                                            

                                            @if($competencia->oculta == 0)
                                                <form action="{{route('competencia.ocultar', $competencia)}}" method = "POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <button class="btn-opacity" type="submit" onclick="return confirm('¿Está seguro que desea ocultar esta competencia?')" onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                                                    style="margin-left: 5px; margin-right: 5px; background-color: #434851; color: white; border: none; padding: 5px 10px; border-radius: 5px; display: inline-flex; justify-content: center; align-items: center;"
                                                    title="Ocultar Competencia">  
                                                        <div class="d-flex align-items-center">                              
                                                            <i class="fa-solid fa-lock" style="font-size: 13px; margin-right: 8px;"></i> <!-- Ícono de FontAwesome -->                                                                                                                    
                                                            <h style="font-size: 15px;">Ocultar competencia</h>
                                                        </div>
                                                    </button>
                                                </form>
                                            @elseif ($competencia->oculta == 1)
                                                <form action="{{route('competencia.ocultar', $competencia)}}" method = "POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <button class="btn-opacity" type="submit" onclick="return confirm('¿Está seguro que desea mostrar esta competencia?')" onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                                                    style="margin-left: 5px; margin-right: 5px; background-color: #434851; color: white; border: none; padding: 5px 10px; border-radius: 5px; display: inline-flex; justify-content: center; align-items: center;"
                                                    title="Mostrar Competencia">                                
                                                        <div class="d-flex align-items-center">
                                                            <i class="fa-solid fa-eye" style="font-size: 13px; margin-right: 8px;"></i> <!-- Ícono de FontAwesome -->                                                        
                                                            <h style="font-size: 15px;">Mostrar competencia</h>
                                                        </div>
                                                    </button>
                                                </form>
                                            
                                            @endif

                                        </div>
                                    @endcan
                                @endauth                                
                            </div>
                    @endforeach
                </div>
        @endforeach        
    @endif

</div>
</x-plantilla-body>

</html>