<x-guest-layout>
    <x-authentication-card-register>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <div class="flex items-center justify-center">
         <b><h1 style="margin-bottom: 15px; font-size: 20px;">Crea una cuenta como Institución</h1></b>
        </div>

        <form id="registroForm" method="POST" action="{{ route('institucion.store') }}">        
            @csrf

            @if ($currentStep == 1)

                <h1><b>Información básica</b></h1>

                <div class="mt-4">
                    <x-label for="name" value="{{ __('Nombre de la institución') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $formPreviousData['step_'.$currentStep]['name'] ?? ''" minlength="5" maxlength="100" required autofocus /> <!-- autocomplete="name" --->
                </div>                                

                <div class="mt-4">
                    <x-label for="tipo" value="{{ __('Tipo de institución') }}" />
                    <x-input id="tipo" class="block mt-1 w-full" type="text" name="tipo" :value="old('tipo') ?? $formPreviousData['step_'.$currentStep]['tipo'] ?? ''" required/> <!-- autocomplete="name" --->
                </div>            
                
                <div class="mt-4">
                    <x-label for="email" value="{{ __('Correo electrónico') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email') ?? $formPreviousData['step_'.$currentStep]['email'] ?? ''" minlength="5" maxlength="50" required /> <!-- autocomplete="username" -->
                </div>

                <div class="mt-4">
                    <x-label for="email_confirmation" value="{{ __('Confirmar correo electrónico') }}" />
                    <x-input id="email_confirmation" class="block mt-1 w-full" type="email" name="email_confirmation" :value="old('email_confirmation') ?? $formPreviousData['step_'.$currentStep]['email_confirmation'] ?? ''" minlength="5" maxlength="50" required /> <!-- autocomplete="username" -->
                    <small id="emailError" style="color: #f87171; display: none;"><div style="margin-top: 10px;"><b><i class="fa fa-exclamation-triangle"></i> Los correos electrónicos no coinciden.</b></div></small>
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="terms" required />

                                <div class="ml-2">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif                                                     
                        
            @elseif ($currentStep == 2)
                
                <h1><b>Ubicación y domicilio</b></h1>

                <div class="mt-4">
                    <x-label for="pais" value="{{ __('País') }}" />
                    <x-input id="pais" class="block mt-1 w-full" type="text" name="pais" :value="old('pais') ?? $formPreviousData['step_'.$currentStep]['pais'] ?? ''" required autofocus/> <!-- autocomplete="name" --->
                </div>

                <div class="mt-4">
                    <x-label for="estado" value="{{ __('Estado') }}" />
                    <x-input id="estado" class="block mt-1 w-full" type="text" name="estado" :value="old('estado') ?? $formPreviousData['step_'.$currentStep]['estado'] ?? ''" required/> <!-- autocomplete="name" --->
                </div>

                <div class="mt-4">
                    <x-label for="ciudad" value="{{ __('Ciudad') }}" />
                    <x-input id="ciudad" class="block mt-1 w-full" type="text" name="ciudad" :value="old('ciudad') ?? $formPreviousData['step_'.$currentStep]['ciudad'] ?? ''" required onblur="obtenerCoordenadasCiudad()"/> <!-- autocomplete="name" --->
                    <small id="error-ciudad" style="color: #f8b471; display: none;"><div style="margin-top: 10px;"><b><i class="fa fa-exclamation-circle"></i> Ciudad no encontrada. </b></div></small>                    
                </div>

                <div class="mt-4">
                    <x-label for="domicilio" value="{{ __('Domicilio') }}" />
                    <x-input id="domicilio" class="block mt-1 w-full" type="text" name="domicilio" :value="old('domicilio') ?? $formPreviousData['step_'.$currentStep]['domicilio'] ?? ''" required/> <!-- autocomplete="name" --->
                </div>                                
                
                <div class="mt-4">
                    <div style="width: 100%; display: flex; justify-content: space-between; align-items: end; margin-bottom: 5px;">                        
                        <!-- Texto 1 alineado a la izquierda -->
                        <x-label for="map" value="{{ __('Ubicación') }}" />                             
                        
                        <!-- Texto 2 alineado a la derecha -->
                        <a onmouseover="this.style.color='#b2b3b3'" onmouseout="this.style.color='#d1d5db'" href="https://maps.google.com/intl/es/" style="font-size: 13px;" target="_blank" rel="noopener noreferrer" title="Apoyo de Búsqueda">
                            Abrir Google Maps
                        </a>
                    </div>                

                    <!-- Objeto debajo -->
                    <div id="map" name="map"></div>                    
                    <small id="error-mapa" style="color: #f87171; display: none;"><div style="margin-top: 10px;"><b><i class="fa fa-exclamation-triangle"></i> Campo obligatorio, seleccione una ubicación en el mapa. </b></div></small>                    

                    <input type="hidden" id="latitud" name="latitud" value = "{{ old('latitud') ?? $formPreviousData['step_'.$currentStep]['latitud'] ?? '' }}"/>
                    <input type="hidden" id="longitud" name="longitud" value = "{{ old('longitud') ?? $formPreviousData['step_'.$currentStep]['longitud'] ?? '' }}"/>
                </div>

            @elseif ($currentStep == 3)

                <h1><b>Información de contacto</b></h1>

                <div class="mt-4">
                    <x-label for="pagina" value="{{ __('Página Web') }}" />
                    <x-input id="pagina" class="block mt-1 w-full" type="url" placeholder="Opcional" name="pagina" :value="old('pagina') ?? $formPreviousData['step_'.$currentStep]['pagina'] ?? ''" autofocus/> <!-- autocomplete="name" --->
                </div>

                <div class="mt-4">
                    <x-label for="telefono" value="{{ __('Número de telefono') }}" />
                    <x-input id="telefono" class="block mt-1 w-full" type="tel" placeholder="Opcional" name="telefono" :value="old('telefono') ?? $formPreviousData['step_'.$currentStep]['telefono'] ?? ''" maxlength="15"/> <!-- autocomplete="name" --->
                </div>

                <div class="mt-4">
                    <x-label for="whatsapp" value="{{ __('WhatsApp') }}" />
                    <x-input id="whatsapp" class="block mt-1 w-full" type="tel"  placeholder="Opcional" name="whatsapp" :value="old('whatsapp') ?? $formPreviousData['step_'.$currentStep]['whatsapp'] ?? ''" maxlength="15"/> <!-- autocomplete="name" --->
                </div> 

            @elseif ($currentStep == 4)

                <!--<h1><b>Registro e inscripción de estudiantes</b></h1>-->

                <h1 style="margin-top: 15px;">Credencial del estudiante</h1>

                <div class="mt-4">
                    <x-label for="siNombreButton" value="{{ __('¿La credencial del estudiante cuenta con el nombre de la institucion?') }}" />                    

                    <div style="margin-top: 5px; display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
                        <div style="align-items: center;">
                            <input id="siNombreButton" type="radio" value=1 name="nombre_escuela_credencial" required
                            {{ old('nombre_escuela_credencial') == 1 ? 'checked' : (($formPreviousData['step_' . $currentStep]['nombre_escuela_credencial'] ?? '') == 1 ? 'checked' : '') }}/> <!-- autocomplete="name" --->
                            <label for="siNombreButton" style="padding-top: 3px;">Si</label>                
                        </div>        

                        <div style="align-items: center;">
                            <input id="noNombreButton" type="radio" value=2 name="nombre_escuela_credencial" required
                            {{ old('nombre_escuela_credencial') == 2 ? 'checked' : (($formPreviousData['step_' . $currentStep]['nombre_escuela_credencial'] ?? '') == 2 ? 'checked' : '') }}/> <!-- autocomplete="name" --->
                            <label for="noNombreButton">No</label>
                        </div>
                    </div>
                </div>

                <div id="nombre_personalizado" class="mt-4" style="display: none">
                    <x-label for="siEscritoButton" value="{{ __('¿El nombre de la institucion está escrito de la siguiente forma?') }}" />
                    "{{ $formPreviousData['step_1']['name'] }}"<br>

                    <div style="margin-top: 5px; display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
                        <div style="align-items: center;">
                            <input id="siEscritoButton" type="radio" value="1" name="nombre_escuela_personalizado" required disabled
                            {{ old('nombre_escuela_personalizado') == '1' ? 'checked' : (($formPreviousData['step_' . $currentStep]['nombre_escuela_personalizado'] ?? '') == '1' ? 'checked' : '') }}/> <!-- autocomplete="name" --->
                            <label for="siEscritoButton" style="margin-top: 3px;">Si</label>                
                        </div>        

                        <div style="align-items: center;">
                            <input id="noEscritoButton" type="radio" value="2" name="nombre_escuela_personalizado" required disabled
                            {{ old('nombre_escuela_personalizado') == '2' ? 'checked' : (($formPreviousData['step_' . $currentStep]['nombre_escuela_personalizado'] ?? '') == '2' ? 'checked' : '') }}/> <!-- autocomplete="name" --->
                            <label for="noEscritoButton">No</label>
                        </div>
                    </div>
                </div>

                <div id="escribir_nombre" class="mt-4" style="display: none">
                    <x-label for="nombre_credencial_escrito" value="{{ __('¿Cómo está escrito el nombre de la institucion ?') }}" />                    
                    <x-input id="nombre_credencial_escrito" class="block mt-1 w-full" type="text" name="nombre_credencial_escrito" :value="old('nombre_credencial_escrito') ?? $formPreviousData['step_'.$currentStep]['nombre_credencial_escrito'] ?? ''" minlength="5" maxlength="100" required disabled/> <!-- autocomplete="name" --->
                </div>       
                
            @elseif ($currentStep == 5)

                <h1><b>Seguridad de la cuenta</b></h1>
                
                <div class="mt-4">
                    <x-label for="password" value="{{ __('Contraseña') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" minlength="8" maxlength="50" required autocomplete="new-password" style="width: 365px; display: inline;" autofocus/>
                    <i onmouseover="this.style.color='gray'" onmouseout="this.style.color='white'" class="fas fa-eye" id="showPassword" onclick="cambiarIcono()" style="margin-left: 10px;"></i>
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" minlength="8" maxlength="50" required autocomplete="new-password" style="width: 365px; display: inline;"/>
                    <i onmouseover="this.style.color='gray'" onmouseout="this.style.color='white'" class="fas fa-eye" id="showPassword_confirmation" onclick="cambiarIcono()" style="margin-left: 10px;"></i>
                    <small id="passwordError" style="color: #f87171; display: none;"><div style="margin-top: 10px;"><b><i class="fa fa-exclamation-triangle"></i> Las contraseñas no coinciden.</b></div></small>
                </div>

            @endif

            <input type="hidden" name="step" value="{{ $currentStep }}">

            <input type="hidden" id="valoresCodificados" name="valoresCodificados"> <!-- Aquí se asignará el nuevo nombre -->

            <div class="flex items-center justify-between mt-4" style="margin-top: 30px;">
                <div class="text-center">                        

                    @if ($previousData || $currentStep != 1 && $currentStep != 1)
                        <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" style="font-size: 14px;" 
                        link="{{ route('institucion.reset') }}" onclick="window.location.href = this.getAttribute('link');">
                            Reiniciar formulario
                        </button><br>
                    @endif
                    
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}" style="font-size: 14px;" title="Iniciar sesión">
                        {{ __('¿Ya estas registrado?') }}
                    </a>                                        
                </div>
                
                @if ($currentStep != 1 && $currentStep != 0)                
                    <!-- Botón anterior -->                    
                    <x-button id="anterior" type="button" class="ml-4"> 
                        Anterior
                    </x-button>                    
                @endif

                <x-button id="siguiente" class="ml-4">
                    @if ($currentStep == 5)
                        {{ __('Registrarse') }}
                    @else
                        {{ __('Siguiente') }}
                    @endif
                </x-button>
        </form>
    </x-authentication-card-register>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Leaflet Control Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>

        // Obtener las coordenadas iniciales desde los inputs ocultos
        var initialLat = document.getElementById('latitud').value;
        var initialLng = document.getElementById('longitud').value;


        // Configurar el mapa centrado en la ubicación almacenada o en una ubicación predeterminada
        var mapCenter = initialLat || initialLng ? [initialLat, initialLng] : [20.6720375, -103.338396];
        
        var map = L.map('map',{
            scrollWheelZoom: false, // Deshabilita el zoom con la rueda del ratón
            zoomControl: false, // Desactivar el control de zoom predeterminado
            attributionControl: false, // Deshabilita los créditos del mapa.
            preferCanvas: true, // Optimizar mapa en celulares
        }).setView(mapCenter, 11); // 11 --> Zoom

        // Inicializar el mapa centrado en Guadalajara (Original)        
        /*var map = L.map('map', {
            scrollWheelZoom: false // Deshabilita el zoom con la rueda del ratón
        }).setView([20.659698, -103.349609], 10);*/ // 10 --> Zoom  


        // Cargar las capas del mapa desde OpenStreetMap
        /*L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            //maxZoom: 18, // Zoom maximo del mapa
            //attribution: '© OpenStreetMap contributors'
        }).addTo(map);*/


        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);


        // Variables para marcador y coordenadas iniciales
        var marker = null; // No se crea un marcador inicialmente              
        var latitudeInput = document.getElementById('latitud');
        var longitudeInput = document.getElementById('longitud');

         // Si hay coordenadas iniciales, ajustar el mapa al área encontrada
        if (initialLat || initialLng) {
            
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
        }
        

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
            placeholder: 'Buscar (Enter).', // Texto de la caja de búsqueda
            collapsed: true, // No colapsar el buscador
            
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


        // Personalizar el control de zoom
        L.control.zoom({
            position: 'bottomleft', // Colocar en la parte superior derecha
            zoomInText: '+', // Texto del botón de acercar
            zoomOutText: '-', // Texto del botón de alejar
            zoomInTitle: 'Acercar', // Título del botón de acercar
            zoomOutTitle: 'Alejar' // Título del botón de alejar
        }).addTo(map);
        

        const prevLat = 20.6720375;
        const prevLon = -103.338396;

        // Función para buscar coordenadas de la ciudad
        async function obtenerCoordenadasCiudad() {
            const city = document.getElementById("ciudad").value.trim(); // Capturar la ciudad ingresada
            
            if (!city) {
                return; // No hacer nada si el valor está vacío  
            }

            var errorCiudad = document.getElementById('error-ciudad');
            
            // Consulta a la API de Nominatim
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${city}`);
            const data = await response.json();

            if (data.length === 0) {
                // Si no se encuentra la ciudad
                //alert("Ciudad no válida. Por favor, escribe una ciudad existente.");                
                errorCiudad.style.display = 'block'; // Muestra el mensaje de error
                return;
            }

            errorCiudad.style.display = 'none'; // oculta el mensaje de error

            // Recuperar coordenadas del primer resultado
            const lat = data[0].lat;
            const lon = data[0].lon;
            
            // Actualizar el centro del mapa
            map.setView([lat, lon], 11);  // 11 --> Zoom

            if(lat != prevLat && lon != prevLon){
                if (marker) {
                    map.removeLayer(marker);
                    marker = null; // Reiniciar la variable
                    latitudeInput.value = "";
                    longitudeInput.value = "";
                }

                prevLat = data[0].lat;
                prevLon = data[0].lon;
            }                

            /*try {
                // Opcional: Añadir marcador
                //L.marker([lat, lon]).addTo(map).bindPopup(`Ciudad: ${city}`).openPopup();
            } catch (error) {
                /*console.error("Error al buscar la ciudad:", error);
                alert("Ocurrió un error al buscar la ciudad.");
            }*/
        }

        //-------------------------------------------------------------> Cambiar ciudad automaticamente con autorrelleno

        /*// Detectar cambios en el input (autorrelleno o pérdida de foco)
        const inputCiudad = document.getElementById("ciudad");
        let previousValueCiudad = inputCiudad.value; // Guardar el valor inicial

        // Detectar pérdida de foco
        inputCiudad.addEventListener("blur", () => {
            if (inputCiudad.value !== previousValueCiudad) {
                // Si el valor cambió, actualizamos
                previousValueCiudad = inputCiudad.value;                
            }
        });

        // Usar MutationObserver para detectar cambios automáticos (sin foco)
        const observer = new MutationObserver(() => {
            if (inputCiudad.value !== previousValueCiudad) {
                // Si el valor cambió, actualizamos
                previousValueCiudad = inputCiudad.value;
                obtenerCoordenadasCiudad();
            }
        });

        // Configuración del MutationObserver
        observer.observe(inputCiudad, {
            attributes: true, // Observa cambios en atributos
            attributeFilter: ["value"], // Solo el atributo 'value'
        });*/

        //-------------------------------------------------------------> Delay entre cada busqueda de API

        /*// Crear función con debounce (espera 1s entre pulsaciones)
        const debounceFunction = debounce(fetchResults, 1000);

        // Evento de entrada en el campo de búsqueda
        document.getElementById("ciudad").addEventListener('input', (event) => {
            debounceFunction(event.target.value); // Llamar con debounce
        });*/

        /*function debounce(func, delay) {
            let timeoutId;

            return function (...args) {
                clearTimeout(timeoutId); // Elimina cualquier temporizador activo
                timeoutId = setTimeout(() => {
                func.apply(this, args); // Llama a la función original después del retraso
                }, delay);
            };
        }*/



        //-------------------------------------------------------------> Mensaje de error

        // Ocultar el mensaje de error cuando ambos valores sean válidos
        function verificarCampos() {
            if (latitudeInput.value || longitudeInput.value) {
                document.getElementById('error-mapa').style.display = 'none'; // Ocultar el mensaje de error
            }
        }

        document.getElementById('registroForm').addEventListener('submit', function (event) {
            var latitude = document.getElementById('latitud');
            var longitude = document.getElementById('longitud');

            // Validar si están vacíos
            if (!latitude.value && !longitude.value) {          
                event.preventDefault(); // Evita que el formulario se envíe
                document.getElementById('error-mapa').style.display = 'block'; // Mostrar el mensaje de error
            } 
            
        });
    </script>

    @if ($currentStep == 4)
        <script>
            // Referencias a inputs
            const NombreButtonSi = document.getElementById('siNombreButton');
            const NombreButtonNo = document.getElementById('noNombreButton');                     

            const EscritoButtonSi = document.getElementById('siEscritoButton');
            const EscritoButtonNo = document.getElementById('noEscritoButton');

            const nombreCredencialEscrito = document.getElementById('nombre_credencial_escrito');   

            // Referencias div    
            const nombrePersonalizado = document.getElementById('nombre_personalizado');

            const escribirNombre = document.getElementById('escribir_nombre');    

            // Pagina recargada
            if(NombreButtonSi.checked){
                EscritoButtonSi.removeAttribute('disabled'); // Habilitar el input   
                EscritoButtonNo.removeAttribute('disabled'); // Habilitar el input   
                
                nombrePersonalizado.style.display = 'block'; // Muestra el input

                // Pagina recargada
                if(EscritoButtonNo.checked){
                    nombreCredencialEscrito.removeAttribute('disabled'); // Habilitar el input   
                    
                    escribirNombre.style.display = 'block'; // Muestra el input
                }
            }

            // Escuchar cambios en el radio button Si
            NombreButtonSi.addEventListener('change', function () {
                if (this.checked) {
                    EscritoButtonSi.removeAttribute('disabled'); // Habilitar el input   
                    EscritoButtonNo.removeAttribute('disabled'); // Habilitar el input   
                    
                    nombrePersonalizado.style.display = 'block'; // Muestra el input
                } 
            });

            // Escuchar cambios en el radio button No
            NombreButtonNo.addEventListener('change', function () {
                if (this.checked) {
                    nombrePersonalizado.style.display = 'none'; // Oculta el input
                    escribirNombre.style.display = 'none'; // Oculta el input

                    EscritoButtonSi.setAttribute('disabled', 'true'); // Deshabilitar el input                
                    EscritoButtonSi.checked = ""; // Limpia el valor de fecha_inicio     
                    
                    EscritoButtonNo.setAttribute('disabled', 'true'); // Deshabilitar el input                
                    EscritoButtonNo.checked = ""; // Limpia el valor de fecha_inicio                     

                    nombreCredencialEscrito.setAttribute('disabled', 'true'); // Deshabilitar el input                
                    nombreCredencialEscrito.value = ""; // Limpia el valor de fecha_inicio  
                }
            });

            // ------------------------------------------------------------------------------>
            
            // Escuchar cambios en el radio button Si
            EscritoButtonNo.addEventListener('change', function () {
                if (this.checked) {
                    nombreCredencialEscrito.removeAttribute('disabled'); // Habilitar el input                   

                    escribirNombre.style.display = 'block'; // Muestra el input
                } 
            });

            // Escuchar cambios en el radio button No
            EscritoButtonSi.addEventListener('change', function () {
                if (this.checked) {
                    escribirNombre.style.display = 'none'; // Oculta el input

                    nombreCredencialEscrito.setAttribute('disabled', 'true'); // Deshabilitar el input                
                    nombreCredencialEscrito.value = ""; // Limpia el valor de fecha_inicio                
                }
            });
        </script>
    @endif

    <script>        

        //document.getElementById('anterior').addEventListener('click', storePreviousData);
        //document.getElementById('siguiente').addEventListener('click', storePreviousData);
        //function storePreviousData () {}

        document.getElementById('anterior').addEventListener('click', function() {
        
            // Obtenemos todos los datos del formulario
            const formData = new FormData(document.getElementById('registroForm'));

            // Creamos un objeto para almacenar los valores
            let valores = {};

            // Llenamos el objeto con los datos del formulario
            formData.forEach(function(value, key) {
                if(value && key != 'valoresCodificados'){
                    valores[key] = value;
                }
            });

            // Codificamos el objeto valores para que sea adecuado para la URL
            const valoresCodificados = encodeURIComponent(JSON.stringify(valores));

            // Creamos la URL con los datos codificados
            const url = `/institucion/anterior/${valoresCodificados}`;

            // Redirigimos al usuario a la nueva URL con los datos
            window.location.href = url;        
        });

    </script>

    <script>        

        document.getElementById('siguiente').addEventListener('click', function() {

            //event.preventDefault(); // Evitar el envío normal del formulario

            // Obtenemos todos los datos del formulario
            const formData = new FormData(document.getElementById('registroForm'));

            //const formulario = document.getElementById("registroForm");

            // Creamos un objeto para almacenar los valores
            let valores = {};

            // Llenamos el objeto con los datos del formulario
            formData.forEach(function(value, key) {
                if(value && key != 'valoresCodificados'){
                    valores[key] = value;
                }
            });

            // Codificamos el objeto valores para que sea adecuado para la URL
            const valoresCodificados = encodeURIComponent(JSON.stringify(valores));

            document.getElementById("valoresCodificados").value = valoresCodificados; 

            //document.getElementById("registroForm").submit(); // Envía el formulario.            

            /*if (formulario.reportValidity()) {
                formulario.submit(); // Enviar solo si es válido
            } 
            else {
                formulario.reportValidity(); // Muestra los errores de validación al usuario
                //formulario.querySelector(":invalid").focus(); // Enfocar el primer campo inválido
            }*/
        });
        
    </script>

    <div style="margin-bottom: 60px;"></div>
</x-guest-layout>
