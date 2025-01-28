<form method="POST" action="{{ route('form.process') }}">
    @csrf

    @if ($currentStep == 1)
        <h2>Paso 1: Información Personal</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $formData['step_1']['nombre'] ?? '') }}" required>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" value="{{ old('apellido', $formData['step_1']['apellido'] ?? '') }}" required>
    @elseif ($currentStep == 2)
        <h2>Paso 2: Información de la Institución</h2>
        <label for="institucion">Institución:</label>
        <input type="text" name="institucion" id="institucion" value="{{ old('institucion', $formData['step_2']['institucion'] ?? '') }}" required>

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $formData['step_2']['direccion'] ?? '') }}" required>
    @elseif ($currentStep == 3)
        <h2>Paso 3: Descripción</h2>
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required>{{ old('descripcion', $formData['step_3']['descripcion'] ?? '') }}</textarea>
    @endif

    <input type="hidden" name="step" value="{{ $currentStep }}">

    <button type="submit">
        @if ($currentStep == 3)
            Enviar
        @else
            Siguiente
        @endif
    </button>
</form>
