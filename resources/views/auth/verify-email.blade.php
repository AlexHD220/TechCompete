<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" style="font-size: 20px;line-height: 1.5; display: flex; justify-content: center; align-items: center; text-align: center; ">
            <i class="fas fa-exclamation-triangle" style="margin-right: 5px; font-size: 35px;"></i>

            {{ __('Antes de continuar, debe verificar la dirección de su correo electrónico.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionó.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        {{ __('Reenviar correo de verificación') }}
                    </x-button>
                </div>
            </form>
                            
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 ml-2">
                    {{ __('Cerrar Sesión') }}
                </button>
            </form>
            
            
        </div>
    </x-authentication-card>
</x-guest-layout>
