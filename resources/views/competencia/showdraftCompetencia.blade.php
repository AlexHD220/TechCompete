<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Mostrar Competencia</title>

    <!-- Estilos -->
    <style>
        /* Estilo para el modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Imagen sin márgenes, centrada */
        .modal-image {
            height: 500px; /* Máxima altura de la imagen */
            object-fit: contain; /* Ajustar imagen sin recortes */
            max-width: 100%; /* Asegura que no se desborde horizontalmente */
            margin: 0; /* Sin márgenes adicionales */
            padding: 0;            
        }

        /* Fondo difuminado */
        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px); /* Difumina el fondo */
            z-index: -1;
        }

        /* Botón para cerrar */
        .close {
            position: absolute;
            top: 5px;
            right: 25px;
            font-size: 45px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            z-index: 1001; /* Asegura que esté encima de la imagen */
        }

        /* Quitar scroll al mostrar el modal */
        body.modal-open {
            overflow: hidden;
        }
    </style>

    <style>
        .sombra {
            /*box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.5);*/
            box-shadow: 0px 0px 5px 3px rgba(255,255,255,0.2);
        }
    </style>
</x-plantilla-head>

<x-plantilla-body>    

    <!-- Modal Imagen -->
    <div id="imageModal" class="modal" style="display: none;">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-overlay" onclick="closeModal()"></div>
        <img src="{{ \Storage::url($competencia->ubicacion_imagen) }}" alt="{{ $competencia->name }}" class="modal-image" style="width: 90%;">
    </div>

    <!--<div style="display: flex; align-items: center; margin-bottom: 20px;">-->
    <div class="text-center" style="margin-bottom: 30px;">

        <!-- Imagen de portada -->        
        <img class="sombra" src="{{ \Storage::url($competencia->ubicacion_imagen) }}" alt="{{ $competencia->name }}" style="width: 80%; max-height: 200px; border-radius: 15px; object-fit: cover; cursor: pointer;" onclick="showModal()">        
                    
    </div>

    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 0px; justify-content: center;">
                            
        <h1 style="margin-top: 8px;"> {{ $competencia -> name }} (Borrador)</h1>         

        @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
            @can('only-superadmin')   
                @if($categoriascount == 0 && $todasregistradas == true)
                    <div style="text-align: center;">
                        <a href="/categoria" style="font-size: 14px;">
                            <i>Ya se han registrado todas las categorías <br>
                                disponibles para esta competencia.</i></a>
                    </div>   
                @elseif($categoriascount > 0)             
                    <button class="btn btn-primary" link="{{ route('competenciacategoria.createdraft', $competencia) }}" 
                    onclick="window.location.href = this.getAttribute('link');">
                        Agregar categoría
                    </button> <!-- Link pendiente --> 
                @elseif($categoriascount == 0 && $competencia->tipo == 'Cualquiera')
                    <div style="text-align: center;">
                        <a href="/categoria" style="font-size: 14px;">
                            <i>Aún no se ha creado ninguna categoría.</i></a>
                    </div>                
                @else
                    <div style="text-align: center;">
                        <a href="/categoria" style="font-size: 14px;">
                            <i>Aún no se ha creado ninguna categoría <br> 
                                compatible con esta competencia.</i></a>
                    </div>
                @endif        
            @endcan
        @endauth
    </div>

    <!--<div style="text-align: justify; text-justify: distribute-all-lines; display: flex; justify-content: center;">-->
    <div style="margin-bottom: 20px; text-align: justify;text-justify: distribute-all-lines;">
        <p style="width: 90%; text-align: justify;">{{ $competencia->descripcion }}</p>
    </div> 

    <div class="d-flex justify-content-between align-items-center" style="width: 90%; margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 15px; justify-content: center;">
        <div>
            <div style="margin-bottom: 15px; display: flex; flex-wrap: wrap; align-items: center;">
                <h5 style="margin-bottom: 0px;"> Inauguración: </h5> 
                <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;"> 
                    {{ \Carbon\Carbon::parse($competencia->fecha)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }} 
                </p>
            </div>
            
            <div style="display: flex; flex-wrap: wrap; align-items: center;">
                <h5 style="margin-bottom: 0px;"> Clausura: </h5> 
                <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;"> 
                    {{ \Carbon\Carbon::parse($competencia->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}  
                </p>
            </div>
        </div>           

                      
        <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
        onclick="window.location.href = '/';"><b>Agenda del evento</b></button> <!-- Ruta pendiente -->

    </div>
    
    <a href="{{ $competencia->mapa_link }}" style="text-decoration: none;" target="_blank" rel="noopener noreferrer" title="Ubicación">        
        <b style="font-size: 25px;">{{ $competencia->ubicacion }}</b>
    </a>
    <p style="margin-bottom: 20px; font-size: 20px;">{{ $competencia->sede }}</p>   
    
    @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
        @can('only-superadmin')   
            @if($competencia->tipo != 'Cualquiera') 
                <div style="display: flex; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
                    <h5 style="margin-bottom: 0px; font-size: 18px;"><i>*<u>Tipo de competencia</u>:</i></h5> 
                    <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;"> 
                        {{$competencia->tipo}}
                    </p>
                </div>
            @endif        
        @endcan
    @endauth

    <h2>Categorías</h2>

    @if(1) <!-- Si no hay competencia_categorias --> <!-- [!] Evaluar si las categorias registradas es igual al total de categorias y mostrar que no hya categorias nuevas por agregar -->
        @if(Gate::allows('only-superadmin')) 
            @if($categoriascount > 0)
                <p style="margin-left: 20px;"><i>Aún no se ha registrado ninguna 
                <a href="/categoria" style="text-decoration: none;">categoría</a> en esta competencia.</i></p>
            @elseif($categoriascount == 0 && $competencia->tipo == 'Cualquiera')
                <p style="margin-left: 20px;"><i>Para registrar una categoría compatible con esta competencia primero debes agregarla en el apartado de 
                <a href="/categoria" style="text-decoration: none;"><u>categorías</u></a>.</i></p>
            @else
                <p style="margin-left: 20px;"><i>Para registrar una categoría primero debes agregarla en el apartado de 
                <a href="/categoria" style="text-decoration: none;"><u>categorías</u></a>.</i></p>
            @endif
        @else
            <p style="margin-left: 20px;"><i>Próximamente podras ver el listado de categorías en este apartado.</i></p>
        @endif
    @else
        <!-- Cuando hay categorias mostrarlas -->
    @endif


    <div style="margin-top: 25px;">
        <!--<a href="/juez">Regresar</a>-->
    </div>

</x-plantilla-body>

<!-- Scripts -->
<script>
    // Mostrar el modal
    function showModal() {
        document.getElementById('imageModal').style.display = 'flex';
        document.body.classList.add('modal-open'); // Deshabilita el scroll
    }

    // Cerrar el modal
    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.body.classList.remove('modal-open'); // Reactiva el scroll
    }
</script>

</html>