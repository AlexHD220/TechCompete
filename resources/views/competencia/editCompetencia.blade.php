<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Editar Competencia</title>

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
        /* Ocultar el input de tipo file */
        #imagen {
            display: none;
        }

        /* Estilo personalizado para el botón */
        .custom-file-label {
            display: inline-block;
            padding: 5px 10px;
            background-color: #eb1616;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .custom-file-label:hover {
            background-color: #bd1616;
        }

        /* Leyenda debajo del botón */
        .file-name {
            margin-top: 5px;
            font-size: 14px;
            color: #555;            
        }
    </style>

    <style>
        .width-descripcion {
            width: 50%;
        }

        /* Pantallas pequeñas: celulares en orientación vertical */
        /*@media (max-width: 768px) {*/
        @media (max-width: 700px) {
            .width-descripcion {
                width: 70%;
            }
        }

        @media (max-width: 450px) {
            .width-descripcion {
                width: 90%;
            }
        }
    </style>

</x-plantilla-head>

<x-plantilla-body>

    @php
        $previousUrl = session('_custom_previous.url');
    @endphp
    
    <h1 style="margin-bottom: 15px;">Editar Competencia</h1>

    <!--editar formulario por medio de la direccion de route:list, esto porque como no tengo un archivo, necesito mandar llamar a la ruta de la lista asesor.update-->
    <form id="formulario" action="{{ route('competencia.update', $competencia)}}" method="post"  enctype="multipart/form-data"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->
        <!--Mostrar errores-->
        @if ($errors->any() || session('missing_fecha'))
            <div class="msgAlerta">                
                <ul>
                    @if(session('missing_fecha'))
                        <li>El campo "Fecha" es obligatorio.</li>
                    @endif

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    @endif
                </ul>

                <!--session('imagen_info')-->
                @if (session('imagen_cargada'))   
                    @if($errors->count() == 1)             
                        <div style="margin-left: 12px;">Debido a este error:</div>
                    @else
                        <div style="margin-left: 12px;">Debido a estos errores:</div>
                    @endif
                    <ul>
                        <!--<li>Por favor, seleccione nuevamente la ubicación en el mapa.</li>-->
                        <li>Por favor, cargue nuevamente la imagen.</li>
                        <!--<p>{{ session('imagen_info.nombre') }}</p>-->
                    </ul>
                @endif
            </div>
            <br>
        @endif

        @csrf <!--permite entrar al formulario muy importante agregar-->
        @method ('PATCH') <!--permite truquear nuestro formulario para editar la informacion-->

        <label for="name"><b> Nombre: </b></label>
        <input type="text" id="name" name="name" style="width: 250px" placeholder="Identificador" required value = "{{old('name') ?? $competencia -> name}}" autofocus><br><br> <!--value = "{{old('name')}}"-->

        <label for="descripcion" style="margin-bottom: 5px;"><b> Descripción: </b></label><br>
        <textarea class="width-descripcion" id="descripcion" name="descripcion" rows="4" style="resize: none;" minlength="1" maxlength="600" required>{{ old('descripcion') ?? $competencia -> descripcion}}</textarea><br><br>        
        
        <label for = "fecha"><b>Fecha: </b></label>
        @if($competencia->enProgreso)
            {{ date('d/m/Y', strtotime($competencia->fecha)) }}<br><br> 
        @else
            <input type="date" id="fecha_competencia" name="fecha" required value = "{{old('fecha') ?? $competencia -> fecha}}" min="{{ now()->addDays(1)->toDateString() }}" max="{{ now()->addYears(2)->toDateString() }}"><br><br>                
        @endif

        <label for = "duracion"><b>Duración: </b></label>
        <input type="number" name="duracion" id="duracion" required value = "{{old('duracion') ?? $competencia -> duracion}}" min="1" step="1" style="width: 50px;"> días <br><br>        

        <label for="tipo" style="margin-bottom: 5px;"><b>Tipo de inscripciones: </b></label><br>
        <select name="tipo" required style="width: 110px; height: 30px;">
            <option selected disabled value=""> - </option>
            <option value="Cualquiera" @selected((old('tipo') ?? $competencia->tipo) == 'Cualquiera')>Cualquiera</option>
            <option value="Equipos" @selected((old('tipo') ?? $competencia->tipo)== 'Equipos')>Equipos</option>
            <option value="Proyectos" @selected((old('tipo') ?? $competencia->tipo)== 'Proyectos')>Proyectos</option>            
        </select><br><br>


        @if(!$competencia->enProgreso)
            <label for = "inicio_registros" style="margin-bottom: 10px;"><b>Fecha de registros: </b></label><br>    

            <label for = "inicio_registros" style="margin-bottom: 15px;"><b> - Inicio: </b></label>       
            <input type="date" id="inicio_registros" name="inicio_registros" required value = "{{old('inicio_registros') ?? $competencia -> inicio_registros}}" min="{{ now()->toDateString() }}" max="{{ \Carbon\Carbon::parse($competencia->fecha)->subDay(1)->format('Y-m-d') }}"><br>

            <label for = "fin_registros"><b> - Cierre: </b></label>
            <input type="date" id="fin_registros" name="fin_registros" required value = "{{ old('fin_registros') ?? $competencia -> fin_registros }}" min="{{ \Carbon\Carbon::parse($competencia->inicio_registros)->addDay(1)->format('Y-m-d') }}" max="{{ $competencia->fecha }}"><br><br>
        @endif

        <!--Seleccion multiple []-->

        <label for="sede"><b> Sede: </b></label>
        <input type="text" id="sede" name="sede" style="width: 250px" placeholder="Nombre del lugar" required value = "{{ old('sede') ?? $competencia -> sede}}"><br><br> <!--value = "{{old('name')}}"-->

        <label for="ubicacion"><b> Ubicación: </b></label>
        <input type="text" id="ubicacion" name="ubicacion" style="width: 250px" placeholder="Nombre del lugar" required value = "{{ old('ubicacion') ?? $competencia -> ubicacion}}"><br><br> <!--value = "{{old('name')}}"-->

        <div style="width: 60%; display: flex; justify-content: space-between; align-items: end; margin-bottom: 5px;">
            <!-- Texto 1 alineado a la izquierda -->
            <label for="map"><b>Dirección:</b></label>
            
            <!-- Texto 2 alineado a la derecha -->
            <a href="https://maps.google.com/intl/es/" style="font-size: 13px;" target="_blank" rel="noopener noreferrer" title="Apoyo de Búsqueda">
                Abrir Google Maps
            </a>
        </div>

        <!-- Objeto debajo -->
        <div id="map" name="map"></div>

        <input type="hidden" id="latitud" name="latitud" value = "{{ old('latitud') ?? $competencia -> latitud}}"/>
        <input type="hidden" id="longitud" name="longitud" value = "{{ old('longitud') ?? $competencia -> longitud}}"/>

        <label for="imagen" style="margin-top: 30px; margin-bottom: 5px;"><b> Cargar imagen: </b></label><br>
        <div>
            <div style="display: flex; align-items: center;">
                <label for="imagen" id="imagen-button" class="custom-file-label">Seleccionar imagen</label>                    
                <i id="circle-check" class="fa-solid fa-file-circle-check" style="margin-left: 10px; font-size: 20px; color: #2bbf29;  opacity: 0; visibility: hidden; transition: opacity 0.5s ease;"></i> <!--display: none;-->
            </div>

            <input type="file" id="imagen" name="imagen" placeholder="imagen" accept=".png, .jpg, .jpeg" onchange="validarImagen(this); circleCheckIcon(this)">

            <b><div id="file-name" class="file-name" style="margin-left: 10px;">Ningún archivo seleccionado</div></b>            
        </div>        
        

        <div style="margin-top: 30px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">            
            <input type="submit" value="Actualizar competencia">
            @if($competencia->publicada) 
                <a href="{{ route('competencia.index') }}">Cancelar</a>
            @else
                <a href="{{ route('competencia.draft') }}">Cancelar</a>
            @endif
            
        </div>

    </form>

    <!--<br>
    <button onclick="window.location.href = '/competencia';">Cancelar</button>-->

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Leaflet Control Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>

        // Obtener las coordenadas iniciales desde los inputs ocultos
        var initialLat = document.getElementById('latitud').value;
        var initialLng = document.getElementById('longitud').value;


        // Configurar el mapa centrado en la ubicación almacenada o en una ubicación predeterminada
        var mapCenter = [initialLat, initialLng];
        
        var map = L.map('map',{
            scrollWheelZoom: false, // Deshabilita el zoom con la rueda del ratón
            zoomControl: false, // Desactivar el control de zoom predeterminado
            attributionControl: false, // Deshabilita los créditos del mapa.
            preferCanvas: true, // Optimizar mapa en celulares
        }).setView(mapCenter, 11); // 11 --> Zoom


        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);


        // Variables para marcador y coordenadas iniciales            
        var latitudeInput = document.getElementById('latitud');
        var longitudeInput = document.getElementById('longitud');

            
        // Almacenar coordenadas de la ubicación
        var latLng = L.latLng(initialLat, initialLng);

        // Crear un "bounding box" alrededor de las coordenadas para ajustar el zoom
        var bounds = latLng.toBounds(500); // 500 metros alrededor del punto

        // Ajustar el mapa al área
        map.fitBounds(bounds);

        // Crear el marcador en la ubicación inicial
        marker = L.marker(latLng, { draggable: true }).addTo(map);        
        
        // Evento para actualizar coordenadas al arrastrar el marcador
        marker.on('dragend', function (event) {
            var position = marker.getLatLng();
            latitudeInput.value = position.lat;
            longitudeInput.value = position.lng;
            verificarCampos(); // Verificar los campos después de actualizar los valores
        });
            
            
        // Actualizar marcador al hacer clic en el mapa
        map.on('click', function (e) {

            // Actualizar la posición del marcador
            marker.setLatLng(e.latlng);
            latitudeInput.value = e.latlng.lat;
            longitudeInput.value = e.latlng.lng;        
        });

        // Agregar el control de búsqueda con autocompletado
        var geocoder = L.Control.geocoder({
            defaultMarkGeocode: false, // No marca automáticamente el lugar, solo muestra sugerencias
            placeholder: 'Buscar (Enter)...', // Texto de la caja de búsqueda
            collapsed: false, // No colapsar el buscador  

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

            // Mover el marcador a la ubicación seleccionada
            marker.setLatLng(center);
            latitudeInput.value = center.lat;
            longitudeInput.value = center.lng;            
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

    </script>
   

    <script>

        // Validar imágen
        document.getElementById('imagen').addEventListener('change', function () {
            var fileName = this.files[0] ? this.files[0].name : "Ningún archivo seleccionado";
            document.getElementById('file-name').textContent = fileName;
        });


        //--------------------------------------> Agregar icono de imagen correcta

        function circleCheckIcon() {
            var input = document.getElementById('imagen');
            var archivo = input.files[0];

            if (archivo) {
                //document.getElementById('circle-check').style.display = 'block';
                const icono = document.getElementById("circle-check");
                icono.style.visibility = "visible"; // Asegura que sea visible
                icono.style.opacity = "1";         // Cambia la opacidad para que se muestre
            }
            else{
                //document.getElementById('circle-check').style.display = 'none';
                const icono = document.getElementById("circle-check");
                icono.style.opacity = "0";         // Cambia la opacidad para ocultar
                setTimeout(() => {
                    icono.style.visibility = "hidden"; // Oculta completamente después de la transición
                }, 100); // Ajusta este tiempo según el valor de `transition` (1 seg)
            }
        }
        
    </script>

</x-plantilla-body>