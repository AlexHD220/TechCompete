<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />
                @if(Auth::id() != 1) <!-- Si el usuario logueado es el super administrador, no se puede eliminar -->
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.delete-user-form')
                    </div>
                @else
                    <div class="mt-10 sm:mt-0" style="text-align: center;">
                    <h1 class="text-lg font-medium text-gray-900 dark:text-gray-100">No es posible eliminar este usuario</h1>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
