<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    
    <title>Institución | Perfil</title>

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


    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet Control Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <style>
        #map {
            height: 300px; /* Ajusta el tamaño del mapa */
            width: 60%;
        }

        /* Pantallas pequeñas: celulares en orientación vertical */
        /*@media (max-width: 768px) {*/
        @media (max-width: 500px) {
            #map {
                width: 100%;
            }
        }

        .leaflet-container {
            position: relative; /* Cambiar a relativa si la barra lateral está encima */
            z-index: 900; /* Mantener por detrás de la barra */
        }

    </style>

    <style>    
        .disabled-input {
            opacity: 0.3; /* Opacidad al 50% */
            pointer-events: none; /* Evita interacción cuando está deshabilitado */
        }

        
        .enabled-input {
            opacity: 1; /* Opacidad al 100% */
            pointer-events: auto; /* Permite interacción */
        }
    </style>

    <style>
        /* Ocultar el input de tipo file */
        #imagenPerfil {
            display: none;
        }

        #imagenPortada {
            display: none;
        }

        /* Leyenda debajo del botón */
        /*.file-name {
            margin-top: 5px;
            font-size: 14px;
            color: #555;            
        }*/
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
        
        @php
            session()->forget('alerta');
        @endphp
    @endif

    @if (!is_null($institucion->ubicacion_imagen))
        <!-- Modal imagen portada -->
        <div id="imagePortadaModal" class="modal" style="display: none;">
            <span class="close" onclick="closePortadaModal()">&times;</span>
            <div class="modal-overlay" onclick="closePortadaModal()"></div>
            <img src="{{ \Storage::url($institucion->ubicacion_imagen) }}" alt="{{ $institucion->name }}" class="modal-image" style="width: 90%;">
        </div>
    @endif

    @if (!is_null($institucion->user->profile_photo_path))
        <!-- Modal imagen perfil -->
        <div id="imagePerfilModal" class="modal" style="display: none;">
            <span class="close" onclick="closePerfilModal()">&times;</span>
            <div class="modal-overlay" onclick="closePerfilModal()"></div>
            <img src="{{ $institucion->user->profile_photo_url }}" alt="{{ $institucion->user->name }}" class="modal-image">
        </div>
    @endif
    
    
    @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
        @can('only-institucion')                
            
            <div style="width: 100%; margin-bottom: 24px; display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 10px;">
                @if (!is_null($institucion->ubicacion_imagen))
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                        <label for = "mostrar_portada"><b>Mostrar portada: </b></label>

                        <div style="height: 20px;">
                            <label class="switch">                            
                            <input type="checkbox" id="mostrar_portada" name="mostrar_portada" {{ old('mostrar_portada') ? 'checked' : ($institucion->portada_oculta ? '' : 'checked') }}>
                            <span class="slider"></span>
                            </label>
                        </div>                                
                    </div>
                @endif

                <button style="margin-left: 10px;" class="btn btn-primary" link="{{ route('institucion.edit') }}" 
                onclick="window.location.href = this.getAttribute('link');">Editar Perfil</button>                
            </div>

        @endcan
    @endauth    


    <!--<div style="display: flex; align-items: center; margin-bottom: 20px;">-->
    @if (!is_null($institucion->ubicacion_imagen))
        <div id="div_portada" class="text-center" style="margin-bottom: 24px;">
            <!-- Imagen de portada -->        
            <img class="sombra" src="{{ \Storage::url($institucion->ubicacion_imagen) }}" alt="{{ $institucion->name }}" style="width: 80%; max-height: 200px; border-radius: 15px; object-fit: cover; cursor: pointer;" onclick="showPortadaModal()">
        </div>
    @else        
        <form id="portadaForm" action="{{ route('institucion.actualizarPortada') }}" method = "POST" enctype="multipart/form-data">

            @csrf <!--permite entrar al formulario muy importante agregar-->

            <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">
            
            <div class="text-center" style="margin-bottom: 25px; width: 100%; display: flex; justify-content: center; align-items: center;">
                <!-- Imagen de portada -->        
                <label for="imagenPortada" class="sombra" onMouseOver="this.style.backgroundColor='#bdbdbd'" onmouseout="this.style.backgroundColor='rgb(255 255 255 / 50%)'" style="width: 80%; height: 200px; border-radius: 15px; cursor: pointer; background-color: rgb(255 255 255 / 50%); display: flex; justify-content: center; align-items: center;">
                    <div id="agregarImagenPortada" style="align-items: center;">
                        <i style="color: black; font-size: 25px; margin-bottom: 10px" class="fa-solid fa-plus"></i>
                        <h2 style="color: black; text-align: center; margin-bottom: 0px;"> Agregar imagen de portada </h2>  
                    </div>                    
                                        
                    <div id="agregandoImagenPortada" style="align-items: center;  display: none;">
                        <i style="color: black; font-size: 25px; margin-bottom: 10px" class="fa-solid fa-spinner"></i>
                        <h2 style="color: black; text-align: center; margin-bottom: 0px;"> Agregando imagen de portada... </h2>
                    </div> 
                </label>
            </div>

            <input type="file" id="imagenPortada" name="imagenPortada" accept=".png, .jpg, .jpeg" onchange="actualizarImagenPortada(this)">

            <!--<b><div id="file-name" class="file-name" style="margin-left: 10px;"></div></b>-->            
        </form>
    @endif

    <div style="display: flex; align-items: center; margin-bottom: 30px;">
        
        <!-- Foto de perfil (solo si existe, sino genera imagen de color con inicial) -->
        <!--@if ($institucion->user->profile_photo_url !== 0)
        @endif-->

        <!-- Imagen de perfil -->
        @if (!is_null($institucion->user->profile_photo_path))
            <div>
                <img src="{{ $institucion->user->profile_photo_url }}" alt="{{ $institucion->user->name }}"style="height: 100px; width: 100px; margin-right: 15px; border-radius: 10px; object-fit: cover; cursor: pointer;" onclick="showPerfilModal()">
            </div>            
        @else        
            <form id="perfilImagenForm" action="{{ route('institucion.actualizarImagenPerfil') }}" method = "POST" enctype="multipart/form-data">

                @csrf <!--permite entrar al formulario muy importante agregar-->

                <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">

                <div class="text-center" style="display: flex; justify-content: center; align-items: center;">
                    <!-- Imagen de perfil -->                      
                    <label for="imagenPerfil" onMouseOver="this.style.backgroundColor='#bdbdbd'" onmouseout="this.style.backgroundColor='rgb(255 255 255 / 50%)'" style="height: 100px; width: 100px; margin-right: 15px; border-radius: 10px; cursor: pointer; background-color: rgb(255 255 255 / 50%); display: flex; justify-content: center; align-items: center;">
                        <div id="agregarImagenPerfil" style="align-items: center;" title="Agregar imagen de perfil">
                            <i style="color: black; font-size: 10px;" class="fa-solid fa-plus"></i>
                            <h style="color: black; text-align: center; margin-bottom: 0px; font-size: 14px;"><b> Agregar imagen de perfil </b></h>  
                        </div>                    
                                            
                        <div id="agregandoImagenPerfil" style="align-items: center; display: none;" >
                            <i style="color: black; font-size: 10px;" class="fa-solid fa-spinner"></i>
                            <h style="color: black; text-align: center; margin-bottom: 0px; font-size: 14px;"><b> Agregando imagen de perfil... </b></h>
                        </div> 
                    </label>

                    <input type="file" id="imagenPerfil" name="imagenPerfil" placeholder="imagenPerfil" accept=".png, .jpg, .jpeg" onchange="actualizarImagenPerfil(this)">

                    <!--<b><div id="file-name" class="file-name" style="margin-left: 10px;"></div></b>-->
                </div>  
            </form>
        @endif
                
        <div>
            <h1 style="margin-top: 8px; margin-bottom: 10px; margin-right: 15px;"> {{ $institucion -> name }} ({{ $institucion -> tipo }})</h1>            
        </div>
        
        <!-- Visitar sitio web -->
    </div>    

    <div style="margin-bottom: 20px; display: flex; flex-wrap: wrap; align-items: center;">        
        <h4 style="margin-bottom: 0px;"> País: </h4>   
        <p style="margin-left: 5px; margin-bottom: 0px; font-size: 22px;"> {{ $institucion -> pais }} </p>   
    </div> 

    <h4> Domicilio: </h4>
    <p style="margin-left: 15px; font-size: 20px; margin-bottom: 0px;">{{ $institucion->domicilio }}</p>   

    <p style="margin-left: 15px; margin-bottom: 20px; font-size: 20px;">{{ $institucion->ciudad }}, {{ $institucion->estado }}</p>   

    <div style="width: calc(60% + 15px); display: flex; justify-content: space-between; align-items: end; margin-bottom: 5px;">
        <!-- Texto 1 alineado a la izquierda -->
        <h4> Ubicacion: </h4>
        
        <!-- Texto 2 alineado a la derecha -->
        <a href="{{ $institucion->mapa_link }}" style="font-size: 13px;" target="_blank" rel="noopener noreferrer" title="Ubicación">
            Abrir en Google Maps
        </a>
    </div>

    <div style="margin-left: 15px;" id="map" name="map"></div>
    

    @if($institucion -> telefono || $institucion -> whatsapp || $institucion -> email_contacto)
        <!--<h4 style="margin-top: 30px; margin-bottom: 15px;"> Informacion de contacto: </h4>-->
        <div class="d-flex justify-content-between align-items-center" style="margin-top: 30px; margin-bottom: 10px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h4 style="margin-bottom: 0px;"> Informacion de contacto: </h4>

            @if($institucion -> pagina_web)
                <div>            
                    <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="white-space: nowrap; font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none;"
                    onclick="window.open('{{ $institucion -> pagina_web }}', '_blank', 'noopener,noreferrer');">
                        <b>Visitar Web</b>
                    </button>                
                </div>
            @endif
        </div>

        @if($institucion -> telefono)
            <div style="margin-left: 15px; margin-bottom: 10px; display: flex; flex-wrap: wrap; align-items: center;">
                <h5 style="margin-bottom: 0px;"> Telefono: </h5> 
                <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;">                 
                    <a target="_blank" href="tel:{{$institucion -> telefono}}">{{ $institucion -> telefono }}</a>
                </p>        
            </div>  
        @endif
        
        @if($institucion -> whatsapp)
            <div style="margin-left: 15px; margin-bottom: 10px; display: flex; flex-wrap: wrap; align-items: center;">
                <h5 style="margin-bottom: 0px;"> Whatsapp: </h5> 
                <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;">                 
                    <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $institucion -> whatsapp }}">{{ $institucion -> whatsapp }}</a>
                </p>        
            </div> 
        @endif 

        @if($institucion -> email_contacto)
            <div style="margin-left: 15px; margin-bottom: 10px; display: flex; flex-wrap: wrap; align-items: center;">
                <h5 style="margin-bottom: 0px;"> Correo electrónico: </h5> 
                <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;"> 
                    <a target="_blank" href="mailto:{{ $institucion -> email_contacto }}">{{ $institucion -> email_contacto }}</a>
                </p>        
            </div> 
        @endif

        <!--@if (!empty($institucion->telefono))
            <h4> Teléfono: </h4>
            <p style="margin-left: 15px; margin-bottom: 20px; font-size: 18px;"> {{ $institucion -> telefono }} </p>
        @endif-->
    @else
        @if($institucion->pagina_web)
            <div>            
                <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="white-space: nowrap; font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; margin-bottom: 20px;"
                onclick="window.open('{{ $institucion -> pagina_web }}', '_blank', 'noopener,noreferrer');">
                    <b>Visitar Web</b>
                </button>                
            </div>
        @endif
    @endif

    @if ($institucion->user->competencias->count() > 0)
        <br>
        <h3>Competencias</h3>

        <ul>
            @foreach($institucion->competencias as $competencia)
                <li>
                    <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('competencia.show', $competencia)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                        {{ $competencia -> nombre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    

</x-plantilla-body>

<!-- Scripts -->
<script>
    // Mostrar el modal
    function showPerfilModal() {
        document.getElementById('imagePerfilModal').style.display = 'flex';
        document.body.classList.add('modal-open'); // Deshabilita el scroll
    }

    // Cerrar el modal
    function closePerfilModal() {
        document.getElementById('imagePerfilModal').style.display = 'none';
        document.body.classList.remove('modal-open'); // Reactiva el scroll
    }
</script>

<!-- Scripts -->
<script>
    // Mostrar el modal
    function showPortadaModal() {
        document.getElementById('imagePortadaModal').style.display = 'flex';
        document.body.classList.add('modal-open'); // Deshabilita el scroll
    }

    // Cerrar el modal
    function closePortadaModal() {
        document.getElementById('imagePortadaModal').style.display = 'none';
        document.body.classList.remove('modal-open'); // Reactiva el scroll
    }
</script>


<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- Leaflet Control Geocoder JS -->
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>

    // Obtener las coordenadas iniciales desde los inputs ocultos
    var initialLat = "{{ $institucion->latitud }}";
    var initialLng = "{{ $institucion->longitud }}";


    // Configurar el mapa centrado en la ubicación almacenada o en una ubicación predeterminada
    var mapCenter = [initialLat, initialLng];    
    
    var map = L.map('map',{
        scrollWheelZoom: false, // Deshabilita el zoom con la rueda del ratón
        zoomControl: false, // Desactivar el control de zoom predeterminado
        attributionControl: false, // Deshabilita los créditos del mapa.
        preferCanvas: true, // Optimizar mapa en celulares
    }).setView(mapCenter, 11); // 11 --> Zoom


    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        
    // Almacenar coordenadas de la ubicación
    var latLng = L.latLng(initialLat, initialLng);

    // Crear un "bounding box" alrededor de las coordenadas para ajustar el zoom
    var bounds = latLng.toBounds(500); // 500 metros alrededor del punto

    // Ajustar el mapa al área
    map.fitBounds(bounds);

    // Crear el marcador en la ubicación inicial
    var marker = L.marker(latLng, { draggable: false }).addTo(map).bindTooltip("Abrir en Google Maps", { permanent: false });
    
    // Agregar evento de clic al marcador para redirigir a la página
    marker.on('click', function () {
        var url = "{{ $institucion->mapa_link }}"; // URL de la base de datos
        window.open(url, '_blank', 'noopener,noreferrer'); // Abrir en nueva pestaña
    });
    
    var initialZoom = map.getZoom();; // Nivel de zoom inicial
    

    // Agregar el control de búsqueda con autocompletado
    var geocoder = L.Control.geocoder({
        defaultMarkGeocode: false, // No marca automáticamente el lugar, solo muestra sugerencias
        placeholder: 'Buscar (Enter)...', // Texto de la caja de búsqueda
        collapsed: true, // Colapsar el buscador  

        resultsLimit: 4, // Limitar a 5 resultados 
        showResultIcons: true, // Mostrar íconos para los resultados                     
        autoComplete: true, // Activar autocompletado    
    })
    .on('markgeocode', function (e) {
        var bbox = e.geocode.bbox;
        var center = e.geocode.center;

        // Ajustar el mapa al área encontrada
        map.fitBounds([
            [bbox.getSouthWest().lat, bbox.getSouthWest().lng],
            [bbox.getNorthEast().lat, bbox.getNorthEast().lng]
        ]);
       
    })
    .addTo(map);


    // Personalizar el control de zoom
    L.control.zoom({
        position: 'bottomleft', // Colocar en la parte superior derecha
        zoomInText: '+', // Texto del botón de acercar
        zoomOutText: '-', // Texto del botón de alejar
        zoomInTitle: 'Acercar', // Título del botón de acercar
        zoomOutTitle: 'Alejar' // Título del botón de alejar
    }).addTo(map);


    // Crear botón de "Restablecer" (inicialmente oculto)
    var resetButton = L.control({ 
        position: 'bottomright' 
    });

    resetButton.onAdd = function (map) {
        var div = L.DomUtil.create('div', 'reset-button');
        div.innerHTML = '<button onclick="resetMap()"> Volver a Centrar </button>';
        div.style.display = 'none'; // Ocultar botón al inicio
        return div;
    };

    resetButton.addTo(map);

    // Detectar si el usuario mueve el mapa y mostrar el botón
    map.on('move', function () {
        if (!isMapAtInitialPosition()) {
            document.querySelector('.reset-button').style.display = 'block';
        }
        else{
            document.querySelector('.reset-button').style.display = 'none'; // Ocultar botón
        }
    });

    // Función para centrar el mapa y ocultar el botón
    function resetMap() {
        map.setView(mapCenter, 11); // Centrar en la posición inicial
        
        map.fitBounds(bounds); // Ajustar el mapa al área
                
        document.querySelector('.reset-button').style.display = 'none'; // Ocultar botón
    }


    // Función para verificar si el mapa está en la posición y zoom iniciales
    function isMapAtInitialPosition() {
        var currentCenter = map.getCenter();
        var currentZoom = map.getZoom();

        return (
            Math.abs(currentCenter.lat - initialLat) < 0.0001 &&
            Math.abs(currentCenter.lng - initialLng) < 0.0001 &&
            currentZoom === initialZoom            
        );
    }

</script>

<script>
    
    const checkbox = document.getElementById('mostrar_portada');             
         
    const divPortada = document.getElementById('div_portada');

    // Pagina recargada
    if(checkbox.checked){
        //divPortada.classList.remove('disabled-input'); // Quitar opacidad del 50%
        divPortada.classList.add('enabled-input'); // Aplicar opacidad del 100%
    }else{
        //divPortada.classList.remove('enabled-input'); // Quitar opacidad del 50%
        divPortada.classList.add('disabled-input'); // Aplicar opacidad del 100%
    }

    // Escuchar cambios en el checkbox
    checkbox.addEventListener('change', function () {
        if (this.checked) {                            
            divPortada.classList.remove('disabled-input'); // Quitar opacidad del 50%
            divPortada.classList.add('enabled-input'); // Aplicar opacidad del 100%
        } 
        else {
            divPortada.classList.remove('enabled-input'); // Quitar opacidad del 100%
            divPortada.classList.add('disabled-input'); // Aplicar opacidad del 50%
            
        }
    });
</script>

<script>
    document.getElementById('mostrar_portada').addEventListener('change', function() {

        let switchInput = this;
    
        // Deshabilitar el switch inmediatamente para evitar cambios rápidos
        switchInput.disabled = true;

        //let mostrar = this.checked ? 1 : 0; // Convierte el valor a 1 o 0
        //let imagen = document.getElementById('imagen_portada');

        // Oculta/Muestra la imagen sin recargar la página
        //imagen.style.display = mostrar ? 'block' : 'none';

        // Enviar el valor al backend usando Fetch API
        fetch("{{ route('institucion.ocultarPortada') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}" // Token de seguridad de Laravel
            },
            body: JSON.stringify()
            /*{
                portada_oculta: mostrar
            })*/
        })        
        .then(data => {            
            // Después de 10 segundos se vuelve a habilitar el switch
            setTimeout(() => {
                switchInput.disabled = false;
            }, 3000);
        })
        .catch(error => {            
            // En caso de error también se vuelve a habilitar el switch después de 10 segundos
            setTimeout(() => {
                switchInput.disabled = false;
            }, 3000);
        });

        /*.then(response => response.json())
        .then(data => console.log(data.message))
        .catch(error => console.error('Error:', error));*/
    });
</script>

<script>

    function actualizarImagenPortada() {
        var input = document.getElementById('imagenPortada');
        var archivo = input.files[0];

        if (archivo) {
            var tipoImagen = archivo.type;
            if (tipoImagen !== 'image/png' && tipoImagen !== 'image/jpeg' && tipoImagen !== 'image/jpg') {        
                input.value = ''; // Limpiar el input para permitir seleccionar otro archivo

                // Usando SweetAlert para notificación
                Swal.fire({
                    title: "¡Hubo un error!",
                    text: "Por favor, selecciona un archivo PNG o JPG válido.",
                    icon: "error",
                    //timer: 3000,
                    //showConfirmButton: false
                });
            }
            else{
                document.getElementById('agregarImagenPortada').style.display = 'none'; // Oculta el mensaje de error  
                document.getElementById('agregandoImagenPortada').style.display = 'block'; // Oculta el mensaje de error  

                document.getElementById("portadaForm").submit(); // Envía el formulario.
            }
        }
    }

    /*function actualizarImagenPortada() {
        var input = document.getElementById('imagenPortada');
        var archivo = input.files[0];

        if (archivo) {
            document.getElementById('agregarImagenPortada').style.display = 'none'; // Oculta el mensaje de error  
            document.getElementById('agregandoImagenPortada').style.display = 'block'; // Oculta el mensaje de error  

            document.getElementById("portadaForm").submit(); // Envía el formulario.
        }
    }*/
    
</script>

<script>

    function actualizarImagenPerfil() {
        var input = document.getElementById('imagenPerfil');
        var archivo = input.files[0];

        if (archivo) {
            var tipoImagen = archivo.type;
            if (tipoImagen !== 'image/png' && tipoImagen !== 'image/jpeg' && tipoImagen !== 'image/jpg') {        
                input.value = ''; // Limpiar el input para permitir seleccionar otro archivo

                // Usando SweetAlert para notificación
                Swal.fire({
                    title: "¡Hubo un error!",
                    text: "Por favor, selecciona un archivo PNG o JPG válido.",
                    icon: "error",
                    //timer: 3000,
                    //showConfirmButton: false
                });
            }
            else{
                document.getElementById('agregarImagenPerfil').style.display = 'none'; // Oculta el mensaje de error  
                document.getElementById('agregandoImagenPerfil').style.display = 'block'; // Oculta el mensaje de error  

                document.getElementById("perfilImagenForm").submit(); // Envía el formulario.
            }
        }
    }
    
    /*function actualizarImagenPerfil() {
        var input = document.getElementById('imagenPerfil');
        var archivo = input.files[0];

        if (archivo) {
            document.getElementById('agregarImagenPerfil').style.display = 'none'; // Oculta el mensaje de error  
            document.getElementById('agregandoImagenPerfil').style.display = 'block'; // Oculta el mensaje de error  

            document.getElementById("perfilImagenForm").submit(); // Envía el formulario.
        }
    }*/
    
</script>

</html>