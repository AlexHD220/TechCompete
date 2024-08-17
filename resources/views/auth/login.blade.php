<x-guest-layout>
    
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Correo electrónico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" minlength="5" maxlength="50" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" style="width: 365px; display: inline;"/>
                <i onmouseover="this.style.color='gray'" onmouseout="this.style.color='white'" class="fas fa-eye" id="showPassword" onclick="cambiarIcono()" style="margin-left: 10px;"></i> <!--Ojo tachado: fas fa-eye-slash / Ojo abierto: fas fa-eye-->
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Recordarme') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-center mt-4" style="margin-top: 20px;">

                <x-button>
                    {{ __('Iniciar Sesión') }}
                </x-button>

            </div>

            <!--<div style="margin-top: 20px;">
                <x-button style="width: 400px;">
                    {{ __('Iniciar Sesión') }}
                </x-button>
            </div>-->

            <div style="margin-top: 15px; text-align: center;">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
            </div>

        </form>

        <div style="margin-top: 30px; text-align: right;">
            <p class="text-gray-400" style="display: inline;">¿No tienes una cuenta?</p>
            <a onmouseover="this.style.color='gray'" onmouseout="this.style.color='white'" href="{{ route('register') }}" style="margin-left: 5px; display: inline-block;">Regístrate</a>
        </div>

    </x-authentication-card>
</x-guest-layout>
