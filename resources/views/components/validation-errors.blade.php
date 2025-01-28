@if ($errors->any() || session('missing_fecha') || session('missing_fecha_fin'))
    <div {{ $attributes }}>
        <div class="font-medium text-red-600 dark:text-red-400">{{ __('¡Vaya! Algo salió mal.') }}</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

            @if(session('missing_nombre_personalizado'))            
                <li>El campo para confirmar el nombre de la institución es obligatorio.</li>
            @endif

            @if(session('missing_nombre_credencial'))
                <li>El campo para ingresar el nombre de la institución es obligatorio.</li>
            @endif
        </ul>
    </div>
@endif
