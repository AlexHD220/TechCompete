<x-guest-layout>
    <x-authentication-card-register>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <div class="flex items-center justify-center">
         <b><h1 style="margin-bottom: 15px; font-size: 20px;">Crea una cuenta como Asesor</h1></b>
        </div>

        <form id="registroForm" method="POST" action="{{ route('asesor.store') }}">
            @csrf

            <div class="mt-4">
                <x-label for="name" value="{{ __('Nombre(s)') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" minlength="3" maxlength="20" required autofocus /> <!-- autocomplete="name" --->
            </div>

            <div class="mt-4">
                <x-label for="lastname" value="{{ __('Apellido(s)') }}" />
                <x-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" minlength="5" maxlength="30" required/> <!-- autocomplete="name" --->
            </div>

            <div class="mt-4">
                <x-label for="telefono" value="{{ __('Número de telefono') }}" />
                <x-input id="telefono" class="block mt-1 w-full" type="tel" placeholder="Opcional" name="telefono" :value="old('telefono')" maxlength="15"/> <!-- autocomplete="name" --->
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Correo electrónico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" minlength="5" maxlength="50" required /> <!-- autocomplete="username" -->
            </div>

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

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <div class="flex items-center w-[90%]">
                    <x-input id="password" class="flex-grow block mt-1 w-full" type="password" name="password" minlength="8" maxlength="50" required autocomplete="new-password" style="display: inline;"/> <!-- width: 365px; -->
                    <i onmouseover="this.style.color='gray'" onmouseout="this.style.color='white'" class="fas fa-eye" id="showPassword" onclick="cambiarIcono()" style="margin-left: 10px; margin-right: 2px"></i>
                </div>
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                <div class="flex items-center w-[90%]">
                    <x-input id="password_confirmation" class="flex-grow block mt-1 w-full" type="password" name="password_confirmation" minlength="8" maxlength="50" required autocomplete="new-password" style="display: inline;"/> <!-- width: 365px; -->
                    <i onmouseover="this.style.color='gray'" onmouseout="this.style.color='white'" class="fas fa-eye" id="showPassword_confirmation" onclick="cambiarIcono()" style="margin-left: 10px; margin-right: 2px"></i>
                </div>
                <small id="passwordError" style="color: #f87171; display: none;"><div style="margin-top: 10px;"><b><i class="fa fa-exclamation-triangle"></i> Las contraseñas no coinciden.</b></div></small>
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

            <div class="flex items-center justify-between mt-4" style="margin-top: 30px;">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}" style="font-size: 14px;">
                    {{ __('¿Ya estas registrado?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Registrarse') }}
                </x-button>
        </form>
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
        document.getElementById('registroForm').addEventListener('submit', function (event) {
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

</x-guest-layout>
