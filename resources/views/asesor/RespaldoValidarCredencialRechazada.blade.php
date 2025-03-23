<x-guest-layout>
    <x-authentication-card-register>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <!--<x-validation-errors class="mb-4" />-->

        <div class="flex items-center justify-center">
         <b><h1 style="margin-bottom: 15px; font-size: 20px;">Comprobar datos personales del asesor</h1></b>
        </div>
        
            <div class="mt-4" style="text-align: justify; margin-bottom: 30px;">
                <p>⚠ <i>Parece que hubo un problema al comparar los datos personales del asesor con los datos de la credencial proporcionada.</i></p>
            </div>

            <div id="datos_ingresados" class="mt-4" style="margin-bottom: 30px;">
                <h2><b>Datos ingresados:</b></h2>
                <ul>
                    <li style="margin-left: 5px;"><strong>- Nombre:</strong> {{ $asesorRequest['name'] ?? '' }}</li>
                    <li style="margin-left: 5px;"><strong>- Apellido(s):</strong> {{ $asesorRequest['lastname'] ?? '' }}</li>
                    @if(0)<!--<li><strong>Escuela:</strong> {{ $asesorRequest['escuela'] ?? '' }}</li>-->
                    <!--<li><strong>Código de Asesor:</strong> {{ $asesorRequest['codigo_asesor'] ?? '' }}</li>-->@endif
                </ul> 

                <div class="flex items-center justify-end mt-4">
                    <x-button id="boton_actualizar_datos" class="ml-4" style="margin-left: 0px;">
                        {{ __('Actualizar datos') }}
                    </x-button>
                </div>
            </div>
            
            <div id="actualizar_datos" class="mt-4" style="display: none; margin-bottom: 30px;">

                <x-validation-errors class="mb-4" />                

                <h2><b>Actualizar datos:</b></h2>

                <form id="datosForm" method="POST" action="{{ route('asesor.validarcredencialrechazadastore', $codigo_rechazo) }}" enctype="multipart/form-data">
                    @csrf                    

                    <input id="tipo" type="hidden" name="tipo" value="datos">

                    <div class="mt-4">
                        <x-label for="name" value="{{ __('Nombre(s)') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $asesorRequest['name'] ?? ''" minlength="3" maxlength="20" required autofocus /> <!-- autocomplete="name" --->
                    </div>

                    <div class="mt-4">
                        <x-label for="lastname" value="{{ __('Apellido(s)') }}" />
                        <x-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname') ?? $asesorRequest['lastname'] ?? ''" minlength="5" maxlength="30" required/> <!-- autocomplete="name" --->
                    </div>

                    <div class="flex items-center justify-end mt-4" style="margin-top: 30px;">
                        <a id="cancelar_datos" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" style="font-size: 14px;">
                            {{ __('Cancelar') }}
                        </a>

                        <x-button id="boton_actualizar_info" class="ml-4" disabled> <!-- New (disabled) -->
                            {{ __('Actualizar') }}
                        </x-button>
                    </div>
                </form>
            </div>

            <div class="mt-4" style="margin-bottom: 30px;">
                <h2><b>Resultados de la comparacion:</b></h2>
                <ul>
                    <li style="margin-left: 5px;"><strong>{{ $data['nombre_valido'] ? '✓' : '✗' }} Nombre:</strong> {{ $data['nombre_valido'] ? 'coincidió' : 'no hubo coincidencia' }}</li>
                    <li style="margin-left: 5px;"><strong>{{ $data['apellido_valido'] ? '✓' : '✗' }} Apellido(s):</strong> {{ $data['apellido_valido'] ? 'coincidió' : 'no hubo coincidencia' }}</li>
                    @if(0)<!--<li><strong>Escuela válida:</strong> {{ $data['escuela_valida'] ? 'coincidió' : 'no hubo coincidencia' }}</li>-->
                    <!--<li><strong>Código válido:</strong> {{ $data['codigo_valido'] ? 'coincidió' : 'no hubo coincidencia' }}</li>-->@endif
                </ul>
            </div>            


            <div id="credencial_proporcionada" class="mt-4" style="margin-bottom: 30px;">
                <h2><b>Credencial proporcionada:</b></h2>
                <img src="{{ \Storage::url($imagenTemporal) }}" alt="Credencial" style="width:90%;">                

                <!-- Botón para volver al formulario para editar los datos -->
                <!--<a href="{{ route('asesor.create') }}" class="btn btn-primary">Modificar Datos</a>-->

                <div class="flex items-center justify-end mt-4" style="margin-top: 20px;">
                    <x-button id="boton_cambiar_credencial" class="ml-4" style="margin-left: 0px;">
                        {{ __('Cambiar imagen') }}
                    </x-button>
                </div>

                @if(0)
                <form action="{{ route('asesor.validarcredencialedit') }}" method="GET">
                    <input type="hidden" name="tipo" value="datos">
                    <button type="submit" class="btn btn-primary">Modificar Datos</button>
                </form>

                <form action="{{ route('asesor.validarcredencialedit') }}" method="GET">
                    <input type="hidden" name="tipo" value="imagen">
                    <button type="submit" class="btn btn-secondary">Modificar Imagen</button>
                </form>
                @endif
            </div>            

            <div id="cambiar_credencial" class="mt-4" style="display: none; margin-bottom: 30px;">
                <x-validation-errors class="mb-4" />                

                <h2><b>Actualizar imagen:</b></h2>

                <form id="datosForm" method="POST" action="{{ route('asesor.validarcredencialrechazadastore', $codigo_rechazo) }}" enctype="multipart/form-data">
                    @csrf                    

                    <input id="tipo" type="hidden" name="tipo" value="imagen">

                    <div class="mt-4">                
                        <x-label for="imagen" value="{{ __('Subir credencial institucional o identificación oficial') }}" />
                        <div>
                            <div style="display: flex; align-items: center; margin-top: 4px;">
                                <label for="imagen" id="imagen-button" class="custom-file-label" style="font-size: 16px;">Seleccionar imagen</label>                    
                                <i id="circle-check" class="fa-solid fa-file-circle-check" style="margin-left: 10px; font-size: 20px; color: #2bbf29;  opacity: 0; visibility: hidden; transition: opacity 0.5s ease;"></i> <!--display: none;-->
                            </div>

                            <input type="file" id="imagen" name="imagen" placeholder="imagen" accept=".png, .jpg, .jpeg" onchange="validarImagen(this); circleCheckIcon(this)">

                            <b><div id="file-name" class="file-name" style="margin-left: 10px;">Ningún archivo seleccionado</div></b>
                            <div id="error-imagen" class="error-message"><i class="fa fa-exclamation-triangle"></i> Campo obligatorio, por favor cargue una imagen.</div>
                        </div>  
                    </div>

                    <div class="flex items-center justify-end mt-4" style="margin-top: 30px;">
                        <a id="cancelar_imagen" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" style="font-size: 14px;">
                            {{ __('Cancelar') }}
                        </a>
                        
                        <x-button id="boton_actualizar_imagen" class="ml-4" disabled> <!-- New (disabled) -->
                            {{ __('Actualizar') }}
                        </x-button>
                    </div>
                </form>
            </div>

            <div class="mt-4" style="text-align: justify; margin-bottom: 30px;">            
                <p>ⓘ <i><b>Nota:</b> Para poder crear una cuenta como asesor es <u>necesario comprobar su identidad</u> por medio de una credencial institucional o identificación oficial.</i></p>            
            </div>
                                    
            
            <div class="flex items-center justify-end mt-4" style="margin-top: 30px;">
                <!-- Botón para enviar la información para revisión manual -->
                @unless($primeraRevision)
                    <form action="{{ route('asesor.revisarcredencialrechazadamanualmente', $codigo_rechazo) }}" method="POST" style="display:inline;">
                        @csrf
                        <!-- Se pueden enviar los datos actuales en campos ocultos para conservarlos -->
                        @if(0)
                            <input type="hidden" name="name" value="{{ $asesorRequest['name'] ?? '' }}">
                            <input type="hidden" name="lastname" value="{{ $asesorRequest['lastname'] ?? '' }}">
                            @if(0)<!--<input type="hidden" name="escuela" value="{{ $asesorRequest['escuela'] ?? '' }}">-->
                            <!--<input type="hidden" name="codigo_asesor" value="{{ $asesorRequest['codigo_asesor'] ?? '' }}">-->@endif
                            <input type="hidden" name="imagen_temporal" value="{{ $imagenTemporal }}">
                        @endif
                                                
                        <x-button onclick="return confirm('¿Está seguro que desea enviar su solicitud a revisión manual?')"  class="ml-4" style="margin-left: 0px;">
                            {{ __('Enviar para Revisión Manual') }}
                        </x-button>
                    </form>
                @endif

    </x-authentication-card-register>

    <div style="margin-bottom: 60px;"></div>

    <script>

        // Validar imágen
        document.getElementById('imagen').addEventListener('change', function () {
            var fileName = this.files[0] ? this.files[0].name : "Ningún archivo seleccionado";
            document.getElementById('file-name').textContent = fileName;

            // Ocultar el mensaje de error si se seleccionó una imagen
            if (this.files.length > 0) {
                document.getElementById('error-imagen').style.display = 'none';
            }
        });

        // Imagen required
        document.getElementById('imagenForm').addEventListener('submit', function (event) {
            var inputImagen = document.getElementById('imagen');

            // Verificar si no hay archivos seleccionados
            if (inputImagen.files.length === 0) {                
                event.preventDefault(); // Evita que el formulario se envíe
                document.getElementById('error-imagen').style.display = 'block'; // Mostrar el mensaje de error
            } else {
                document.getElementById('error-imagen').style.display = 'none'; // Oculta el mensaje de error         
            }
            
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

                document.getElementById("boton_actualizar_imagen").disabled = false; // New
            }
            else{
                //document.getElementById('circle-check').style.display = 'none';
                const icono = document.getElementById("circle-check");
                icono.style.opacity = "0";         // Cambia la opacidad para ocultar
                setTimeout(() => {
                    icono.style.visibility = "hidden"; // Oculta completamente después de la transición
                }, 100); // Ajusta este tiempo según el valor de `transition` (1 seg)

                document.getElementById("boton_actualizar_imagen").disabled = true; // New
            }
        }
        
    </script>

    <script>
        // New

        // Pagina recargada
        if(document.getElementById("name").value != "{{ $asesorRequest['name'] }}" || document.getElementById("lastname").value != "{{ $asesorRequest['lastname'] }}" ){
            document.getElementById("boton_actualizar_info").disabled = false; // New
        }        

        document.getElementById("name").addEventListener('input', function () {
            if(document.getElementById("name").value && document.getElementById("lastname").value){    
                if (document.getElementById("name").value != "{{ $asesorRequest['name'] }}" || document.getElementById("lastname").value != "{{ $asesorRequest['lastname'] }}") {
                    document.getElementById("boton_actualizar_info").disabled = false; // New
                } else {
                    document.getElementById("boton_actualizar_info").disabled = true; // New                   
                }
            }
            else{
                document.getElementById("boton_actualizar_info").disabled = true; // New 
            }
        });

        document.getElementById("lastname").addEventListener('input', function () {
            if(document.getElementById("name").value && document.getElementById("lastname").value){    
                if (document.getElementById("name").value != "{{ $asesorRequest['name'] }}" || document.getElementById("lastname").value != "{{ $asesorRequest['lastname'] }}") {
                    document.getElementById("boton_actualizar_info").disabled = false; // New
                } else {
                    document.getElementById("boton_actualizar_info").disabled = true; // New                   
                }
            }
            else{
                document.getElementById("boton_actualizar_info").disabled = true; // New 
            }
        });

        // New
    </script>

    <script>
        // div
        const datosIngresados = document.getElementById('datos_ingresados');
        const actualizarDatos = document.getElementById('actualizar_datos');                     


        // Pagina recargada
        if(document.getElementById("name").value != "{{ $asesorRequest['name'] }}" || document.getElementById("lastname").value != "{{ $asesorRequest['lastname'] }}" ){
            actualizarDatos.style.display = 'block'; // Muestra el div
            datosIngresados.style.display = 'none'; // ocultar el div
        }

        // ------------------------------------------------------------------------------>

        document.getElementById("boton_actualizar_datos").addEventListener("click", function() {
            actualizarDatos.style.display = 'block'; // Muestra el div
            datosIngresados.style.display = 'none'; // ocultar el div
            document.getElementById("boton_cambiar_credencial").disabled = true;
        });

        document.getElementById("cancelar_datos").addEventListener("click", function(event) {
            event.preventDefault(); // Evita que el enlace navegue
            datosIngresados.style.display = 'block'; // Muestra el div
            actualizarDatos.style.display = 'none'; // ocultar el div
            document.getElementById("boton_cambiar_credencial").disabled = false;
        });

    </script>

    <script>
        // div
        const credencialProporcionada = document.getElementById('credencial_proporcionada');
        const cambiarCredencial = document.getElementById('cambiar_credencial');                     

        // ------------------------------------------------------------------------------>

        document.getElementById("boton_cambiar_credencial").addEventListener("click", function() {
            cambiarCredencial.style.display = 'block'; // Muestra el div
            credencialProporcionada.style.display = 'none'; // ocultar el div
            document.getElementById("boton_actualizar_datos").disabled = true;
            
            //document.getElementById("boton_actualizar_imagen").disabled = true; // New
        });

        document.getElementById("cancelar_imagen").addEventListener("click", function(event) {
            event.preventDefault(); // Evita que el enlace navegue
            credencialProporcionada.style.display = 'block'; // Muestra el div
            cambiarCredencial.style.display = 'none'; // ocultar el div
            document.getElementById("boton_actualizar_datos").disabled = false;
        });

    </script>

</x-guest-layout>
