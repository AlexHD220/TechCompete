<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Competencia | Formulario</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet Control Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <style>
        #map {
            height: 300px; /* Ajusta el tamaño del mapa */
            width: 60%;
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
        .error-message {
            /*color: red;*/
            color: #f87171;
            font-size: 14px;
            display: none; /* Oculto por defecto */
            margin-top: 10px;            
        }
    </style>
</x-plantilla-head>

<x-plantilla-body>
    
    <h1 style="margin-bottom: 15px;">Registrar Competencia</h1>

    <!--<form action = "{{ route('competencia.store') }}">-->

                                              <!-- Agregar archivos al formulario -->
    <form id="formulario" action="/competencia" method="post" enctype="multipart/form-data" id = "createCompetencia"> <!--la diagonal me envia al principio de la url "techcompete.test/"-->

        <!--Mostrar errores-->
        @if ($errors->any())
            <div class="msgAlerta">                
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <div style="margin-left: 12px;">Debido a este error:</div>
                <ul>
                    <li>Por favor, seleccione nuevamente la ubicación en el mapa.</li>
                    <li>Por favor, cargue nuevamente la imagen.</li>
                </ul>
            </div>
            <br>
        @endif

        @csrf <!--permite entrar al formulario muy importante agregar-->

        <label for="name"><b> Nombre: </b></label>
        <input type="text" id="name" name="name" style="width: 250px" placeholder="Identificador" required value = "{{ old('name') }}" autofocus><br><br> <!--value = "{{old('name')}}"-->

        <label for="descripcion" style="margin-bottom: 5px;"><b> Descripción: </b></label><br>
        <textarea id="descripcion" name="descripcion" rows="4" style="resize: none; width: 50%;" minlength="1" maxlength="600" required>{{ old('descripcion') }}</textarea><br><br>

        <label for = "fecha"><b>Fecha: </b></label>
        <input type="date" name="fecha" required value = "{{ old('fecha') }}" min="{{ now()->toDateString() }}" max="{{ now()->addYears(2)->toDateString() }}"><br><br>

        <label for = "duracion"><b>Duración: </b></label>
        <input type="number" name="duracion" id="duracion" required value = "{{ old('duracion') }}" min="1" max="31" step="1" style="width: 50px;"> días <br><br>

        <label for="tipo"><b>Tipo de inscripciones: </b></label><br>
        <select name="tipo" required style="width: 90px;">
            <option selected disabled value=""> - </option>
            <option value="Equipos" @selected(old('tipo') == 'Equipos')>Equipos</option>
            <option value="Proyectos" @selected(old('tipo') == 'Proyectos')>Proyectos</option>
        </select><br><br>

        <!--Seleccion multiple []-->

        <label for="sede"><b> Sede: </b></label>
        <input type="text" id="sede" name="sede" style="width: 250px" placeholder="Nombre del lugar" required value = "{{ old('sede') }}"><br><br> <!--value = "{{old('name')}}"-->

        <div style="width: 60%; display: flex; justify-content: space-between; align-items: end; margin-bottom: 5px;">
            <!-- Texto 1 alineado a la izquierda -->
            <label for="map"><b>Ubicación:</b></label>
            
            <!-- Texto 2 alineado a la derecha -->
            <a href="https://maps.google.com/intl/es/" style="font-size: 13px;" target="_blank" rel="noopener noreferrer">Abrir Google Maps</a>
        </div>

        <!-- Objeto debajo -->
        <div id="map" name="map"></div>
        <div id="error-mapa" class="error-message"><i class="fa fa-exclamation-triangle"></i> Campo obligatorio, por favor seleccione una ubicación en el mapa.</div>

        <input type="hidden" id="latitude" name="latitude"/>
        <input type="hidden" id="longitude" name="longitude"/>

        <label for="imagen" style="margin-top: 30px; margin-bottom: 5px;"><b> Cargar imagen: </b></label><br>
        <div>
            <div style="display: flex; align-items: center;">
                <label for="imagen" id="imagen-button" class="custom-file-label">Seleccionar imagen</label>                    
                <i id="circle-check" class="fa-solid fa-file-circle-check" style="margin-left: 10px; font-size: 20px; color: #2bbf29; display: none;"></i>
            </div>

            <input type="file" id="imagen" name="imagen" placeholder="imagen" accept=".png, .jpg, .jpeg" onchange="validarImagen(this); circleCheckIcon(this)">

            <b><div id="file-name" class="file-name" style="margin-left: 10px;">Ningún archivo seleccionado</div></b>
            <div id="error-imagen" class="error-message"><i class="fa fa-exclamation-triangle"></i> Campo obligatorio, por favor cargue una imagen.</div>
        </div>


        <input type="submit" value="Registrar competencia" style="margin-top: 30px;"> 
        <a href="{{ route('competencia.index') }}" style="margin-left:10px;">Cancelar</a> 

    </form>

    <!--<br>
    <button onclick="window.location.href = '/competencia';">Cancelar</button>-->

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Leaflet Control Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        // Inicializar el mapa centrado en Guadalajara
        var map = L.map('map', {
            scrollWheelZoom: false // Deshabilita el zoom con la rueda del ratón
        }).setView([20.659698, -103.349609], 10); // 10 --> Zoom

        // Cargar las capas del mapa desde OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            //attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Variables para marcador y coordenadas iniciales
        var marker = null; // No se crea un marcador inicialmente
        var latitudeInput = document.getElementById('latitude');
        var longitudeInput = document.getElementById('longitude');

        // Actualizar marcador al hacer clic en el mapa
        map.on('click', function (e) {
            if (!marker) {
                // Crear marcador si no existe
                marker = L.marker(e.latlng, { draggable: true }).addTo(map);
                
                // Evento para actualizar coordenadas al arrastrar el marcador
                marker.on('dragend', function (event) {
                    var position = marker.getLatLng();
                    latitudeInput.value = position.lat;
                    longitudeInput.value = position.lng;
                    verificarCampos(); // Verificar los campos después de actualizar los valores
                });
            }

            // Actualizar la posición del marcador
            marker.setLatLng(e.latlng);
            latitudeInput.value = e.latlng.lat;
            longitudeInput.value = e.latlng.lng;
            verificarCampos(); // Verificar los campos después de actualizar los valores
        });

        // Agregar el control de búsqueda con autocompletado
        var geocoder = L.Control.geocoder({
            defaultMarkGeocode: false, // No marca automáticamente el lugar, solo muestra sugerencias
            placeholder: 'Buscar domicilio (Presiona Enter).', // Texto de la caja de búsqueda
            collapsed: false // No colapsar el buscador
        })
        .on('markgeocode', function (e) {
            var bbox = e.geocode.bbox;
            var center = e.geocode.center;

            // Ajustar el mapa al área encontrada
            map.fitBounds([
                [bbox.getSouthWest().lat, bbox.getSouthWest().lng],
                [bbox.getNorthEast().lat, bbox.getNorthEast().lng]
            ]);

            if (!marker) {
                // Crear marcador si no existe
                marker = L.marker(center, { draggable: true }).addTo(map);
                marker.on('dragend', function (event) {
                    var position = marker.getLatLng();
                    latitudeInput.value = position.lat;
                    longitudeInput.value = position.lng;
                    verificarCampos(); // Verificar los campos después de actualizar los valores
                });
            }

            // Mover el marcador a la ubicación seleccionada
            marker.setLatLng(center);
            latitudeInput.value = center.lat;
            longitudeInput.value = center.lng;
            verificarCampos(); // Verificar los campos después de actualizar los valores
        })
        .addTo(map);


        // Ocultar el mensaje de error cuando ambos valores sean válidos
        function verificarCampos() {
            if (latitudeInput.value || longitudeInput.value) {
                document.getElementById('error-mapa').style.display = 'none'; // Ocultar el mensaje de error
            }
        }

        document.getElementById('formulario').addEventListener('submit', function (event) {
            var latitude = document.getElementById('latitude');
            var longitude = document.getElementById('longitude');

            // Validar si están vacíos
            if (!latitude.value && !longitude.value) {          
                event.preventDefault(); // Evita que el formulario se envíe
                document.getElementById('error-mapa').style.display = 'block'; // Mostrar el mensaje de error
            } 
            
        });
    </script>
   

    <script>
        document.getElementById('imagen').addEventListener('change', function () {
            var fileName = this.files[0] ? this.files[0].name : "Ningún archivo seleccionado";
            document.getElementById('file-name').textContent = fileName;

            // Ocultar el mensaje de error si se seleccionó una imagen
            if (this.files.length > 0) {
                document.getElementById('error-imagen').style.display = 'none';
            }
        });

        document.getElementById('formulario').addEventListener('submit', function (event) {
            var inputImagen = document.getElementById('imagen');

            // Verificar si no hay archivos seleccionados
            if ((inputImagen.files.length === 0) && (latitude.value || longitude.value)) {                
                event.preventDefault(); // Evita que el formulario se envíe
                document.getElementById('error-imagen').style.display = 'block'; // Mostrar el mensaje de error
            } else {
                document.getElementById('error-imagen').style.display = 'none'; // Oculta el mensaje de error         
            }
            
        });

        function circleCheckIcon() {
            var input = document.getElementById('imagen');
            var archivo = input.files[0];

            if (archivo) {
                document.getElementById('circle-check').style.display = 'block';
            }
            else{
                document.getElementById('circle-check').style.display = 'none';
            }
        }
        
    </script>

</x-plantilla-body>