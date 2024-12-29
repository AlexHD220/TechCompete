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
                <x-input id="telefono" class="block mt-1 w-full" type="tel" placeholder="Opcional" name="telefono" :value="old('telefono')" maxlength="10"/> <!-- autocomplete="name" --->
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Correo electrónico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" minlength="5" maxlength="50" required /> <!-- autocomplete="username" -->
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" minlength="8" maxlength="50" required autocomplete="new-password" style="width: 365px; display: inline;"/>
                <i onmouseover="this.style.color='gray'" onmouseout="this.style.color='white'" class="fas fa-eye" id="showPassword" onclick="cambiarIcono()" style="margin-left: 10px;"></i>
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" minlength="8" maxlength="50" required autocomplete="new-password" style="width: 365px; display: inline;"/>
                <i onmouseover="this.style.color='gray'" onmouseout="this.style.color='white'" class="fas fa-eye" id="showPassword_confirmation" onclick="cambiarIcono()" style="margin-left: 10px;"></i>
                <small id="passwordError" style="color: #f87171; display: none;"><div style="margin-top: 10px;"><b>Las contraseñas no coinciden.</b></div></small>
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
</x-guest-layout>
