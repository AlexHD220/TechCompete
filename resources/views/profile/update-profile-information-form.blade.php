<x-form-section submit="updateProfileInformation">
    
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                @can('mail-verificado')
                    <x-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                        {{ __('Subir Nueva Foto') }}
                    </x-secondary-button>                

                    @if ($this->user->profile_photo_path)
                        <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                            {{ __('Eliminar Foto') }}
                        </x-secondary-button>
                    @endif
                @endcan

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Last Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Nombre(s)') }}" />
            @can('mail-verificado')
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            @else
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" readonly />
            @endcan
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Name -->
        @cannot('only-institucion')
            <div class="col-span-6 sm:col-span-4">
                <x-label for="lastname" value="{{ __('Apellido(s)') }}" />
                @can('mail-verificado')
                    <x-input id="lastname" type="text" class="mt-1 block w-full" wire:model="state.lastname" required autocomplete="lastname" />
                @else
                    <x-input id="lastname" type="text" class="mt-1 block w-full" wire:model="state.lastname" required autocomplete="lastname" readonly />
                @endcan
                <x-input-error for="lastname" class="mt-2" />
            </div>
        @endcan

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            @can('mail-verificado')
                <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            @else
                <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" readonly/>
            @endcan
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <div style="margin-top: 30px;">
                    <p class="text-sm mt-2 dark:text-white">
                        {{ __('Su dirección de correo electrónico no está verificada.') }}<br>

                        <button type="button" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" wire:click.prevent="sendEmailVerification">
                            {{ __('Haga clic aquí para volver a enviar el correo electrónico de verificación.') }}
                        </button>
                    </p>
                </div>                

                @if ($this->verificationLinkSent)
                    <div style="margin-top: 15px;">
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.') }}
                        </p>
                    </div>
                @endif
            @endif
        </div>
    </x-slot>

    @can('mail-verificado')
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                <b style="color: #41ef1f;">{{ __('Guardado.') }}</b>
            </x-action-message>

            <x-button wire:loading.attr="disabled" wire:target="photo" style="margin-bottom: 10px;">
                {{ __('Guardar') }}
            </x-button>
        </x-slot>
    @endcan
</x-form-section>
