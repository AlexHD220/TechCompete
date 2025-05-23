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

<style>
        .categorias-container {
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
            .categorias-container {
                grid-template-columns: repeat(2, 1fr); /* Cambiar a dos columnas por fila */
            }
        }

        /* Pantallas pequeñas: celulares en orientación vertical */
        /*@media (max-width: 768px) {*/
        @media (max-width: 650px) {
            .categorias-container {
                grid-template-columns: 1fr; /* Cambiar a una columna por fila */
            }
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
        
        <h1 style="margin-top: 8px;">{{ $competencia->publicada ? '' : 'Borrador ' }} {{ $competencia -> name }}</h1> 

        @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
            @can('only-superadmin')  
                @if(!$competencia->enProgreso && !$competencia->pasada)
                    @if($categoriascount == 0 && $todasregistradas == true)
                        <div style="text-align: center;">
                            <a href="/categoria" style="font-size: 14px;">
                                <i>Ya se han registrado todas las categorías <br>
                                    disponibles para esta competencia.</i></a>
                        </div>   
                    @elseif($categoriascount > 0 && $competencia->publicada)             
                        <button class="btn btn-primary" link="{{ route('competenciacategoria.create', $competencia) }}" 
                        onclick="window.location.href = this.getAttribute('link');">
                            Agregar categoría
                        </button>
                    @elseif($categoriascount > 0 && !$competencia->publicada)             
                        <button class="btn btn-primary" link="{{ route('competenciacategoria.createdraft', $competencia) }}" 
                        onclick="window.location.href = this.getAttribute('link');">
                            Agregar categoría
                        </button>
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
        
        @if(0)
            <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
            link="{{ $competencia->publicada ? route('competencia.agenda', $competencia) : route('competencia.agenda', $competencia) }}" 
            onclick="window.location.href = this.getAttribute('link');"><b>Agenda del evento</b></button> <!-- Ruta pendiente -->
        @endif

    </div>
    
    <a href="{{ $competencia->mapa_link }}" style="text-decoration: none;" target="_blank" rel="noopener noreferrer" title="Ubicación">        
        <b style="font-size: 25px;">{{ $competencia->ubicacion }}</b>
    </a>
    <p style="margin-bottom: 20px; font-size: 20px;">{{ $competencia->sede }}</p>   
    
    @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
        @can('only-superadmin') 

            <div style="display: flex; flex-wrap: wrap; align-items: center; margin-bottom: 20px; margin-left: 10px;">
                <h5 style="margin-bottom: 0px; font-size: 18px;"><i>*<u>Tipo de competencia</u>:</i></h5> 
                <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;"> 
                    {{ strtolower($competencia->tipo) }}
                </p>
            </div>   

        @endcan
    @endauth

    <h2>Categorías</h2>

    @if($competenciaCategorias->count() == 0) <!-- Si no hay competencia_categorias --> <!-- [!] Evaluar si las categorias registradas es igual al total de categorias y mostrar que no hya categorias nuevas por agregar -->
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
    @else <!-- Cuando hay categorias mostrarlas -->
        <!-- Contenedor con grid -->
        <div class="categorias-container" style="margin-top: 15px;  margin-bottom: 15px;">
            @foreach ($competenciaCategorias as $competenciaCategoria)            
                    <!--<div class="categorias-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;">-->
                    <div class="categorias-card">

                        <div class="text-center" style="margin-top: 0px;">
                            <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" 
                            href="{{ $competencia->publicada ? route('competenciacategoria.show', [$competencia, $competenciaCategoria]) : route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]) }}" 
                            style="text-decoration: none; color: inherit;">
                                <b style="font-size: 25px;">"{{ $competenciaCategoria->categoria->name }}"</b>
                            </a>
                        </div>

                        <div class="text-center" style="margin-top: 5px; font-size: 16px;">                            
                            @if(\Carbon\Carbon::parse($competencia->fecha)->isAfter(\Carbon\Carbon::now()->startOfDay()))
                                @if($competenciaCategoria->registro_personalizado)
                                    @if(\Carbon\Carbon::parse($competenciaCategoria->fin_registros)->isBefore(\Carbon\Carbon::now()->startOfDay()))                                  
                                        <i>Inscripciones Cerradas</i>
                                    @else
                                        @if(\Carbon\Carbon::parse($competenciaCategoria->inicio_registros)->isBefore(\Carbon\Carbon::now()->startOfDay()))  
                                            <b>Cierre de inscripciones:</b><br>
                                            {{ \Carbon\Carbon::parse($competenciaCategoria->fin_registros)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                                        @else
                                            <b>Inicio de inscripciones:</b><br>
                                            {{ \Carbon\Carbon::parse($competenciaCategoria->inicio_registros)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                                        @endif
                                    @endif
                                @else
                                    @if(\Carbon\Carbon::parse($competencia->fin_registros)->isBefore(\Carbon\Carbon::now()->startOfDay()))                                  
                                        <i>Inscripciones Cerradas</i>
                                    @else
                                        @if(\Carbon\Carbon::parse($competencia->inicio_registros)->isBefore(\Carbon\Carbon::now()->startOfDay()))  
                                            <b>Cierre de inscripciones:</b><br>
                                            {{ \Carbon\Carbon::parse($competencia->fin_registros)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                                        @else
                                            <b>Inicio de inscripciones:</b><br>
                                            {{ \Carbon\Carbon::parse($competencia->inicio_registros)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                                        @endif
                                    @endif
                                @endif
                            @else
                                <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" 
                                href="{{ $competencia->publicada ? route('competenciacategoria.show', [$competencia, $competenciaCategoria]) : route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]) }}" 
                                style="text-decoration: none; color: inherit;">
                                    <i>Ver detalles</i>
                                </a>
                            @endif
                        </div>

                        @auth
                            @can('only-superadmin')
                                @if(!$competencia->enProgreso && !$competencia->pasada) 

                                    <div class="text-center" style="margin-top: 10px;">
                                        <!-- Botón para Editar -->
                                        <a href="{{ $competencia->publicada ? route('competenciacategoria.edit', [$competencia, $competenciaCategoria]) : route('competenciacategoria.editdraft', [$competencia, $competenciaCategoria]) }}" 
                                        onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                                        style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #434851; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                        title="Editar Categoría">                                
                                            <i class="fas fa-edit" style="font-size: 20px;"></i> <!-- Ícono de FontAwesome -->
                                        </a>    
                                        
                                        <form action="{{route('competenciacategoria.destroy', [$competencia, $competenciaCategoria])}}" method = "POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')

                                            <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">
                                            
                                            <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta categoría de la competencia?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                            style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                            title="Eliminar Categoría de la Competencia">
                                                <i class="fas fa-trash" style="font-size: 20px;"></i> <!-- Ícono de FontAwesome -->
                                            </button>
                                        </form>   
                                    </div>

                                @endif
                            @endcan
                        @endauth
                    </div>
            @endforeach
        </div>
    @endif
    <br>

    <!--<div style="margin-top: 25px;">
        <a href="/juez">Regresar</a>
    </div>-->

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