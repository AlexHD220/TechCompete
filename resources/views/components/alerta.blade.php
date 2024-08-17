<!-- declarar variables dentro del mismo archivo
@props([
    'tipoAlerta' = 'info';    
    'tipoAlerta';  <-- obligatorio  el dato--
])-->

<!--inyectar tag-->

<div class="alert alert-{{ $tipoAlerta }}">
    <!-- Because you are alive, everything is possible. - Thich Nhat Hanh -->
    <h1>{{ $slot }}</h1>
</div>

