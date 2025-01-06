<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Competencias</title>
</x-plantilla-head>

<x-plantilla-body>

<!--@php
$timestampNow = now()->toDateString();
@endphp
<p>Timestamp actual: {{ $timestampNow }}</p>-->

<div>
    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
        <h1 style="display: inline;">Listado de Competencias</h1>
        @auth
            @can('only-superadmin')
                @if ($categorias->count() > 0)
                    <button class="btn btn-primary" onclick="window.location.href = '/competencia/create';">Registrar nueva competencia</button>
                @else
                    <div style="text-align: center;">
                        <a href="/categoria" style="font-size: 14px;"><i>Para registrar una nueva competencia,<br>primero agrega sus categorías.</i></a>
                    </div>
                @endif
            @endcan
        @endauth
    </div>

    @if ($competencias->count() == 0)
        @can('only-superadmin')
            <p style="margin-left: 20px;"><i>Aún no hay ninguna competencia registrada.</i></p>
        @else
            <p style="margin-left: 20px;"><i>No hay nuevas competencias disponibles.</i></p>
        @endcan
    @endif

    <!-- Contenedor con grid -->
    <div class="competencias-container" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        @foreach ($competencias as $competencia)
            @if (strtotime($competencia->fecha) >= strtotime(now()->toDateString()))
                <div class="competencia-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;">
                    <div class="text-center">
                        <img src="{{ \Storage::url($competencia->ubicacion_imagen) }}" alt="Logo competencia" style="width: 100%; max-height: px; object-fit: cover;">
                    </div>
                    <div class="text-center" style="margin-top: 10px;">
                        <a href="{{ route('competencia.show', $competencia) }}" style="text-decoration: none; color: inherit;">
                            <b style="font-size: 20px;">{{ $competencia->identificador }}</b>
                        </a>
                    </div>
                    <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                        Fecha: {{ date('d/m/Y', strtotime($competencia->fecha)) }}
                    </div>

                    @auth
                        @can('only-superadmin')
                            <div class="text-center" style="margin-top: 10px;">
                                <a href="{{ route('competencia.edit', $competencia) }}" class="btn btn-secondary" style="margin-right: 10px;">Editar</a>
                                <form action="{{ route('competencia.destroy', $competencia) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar la publicación de esta competencia?')">Eliminar</button>
                                </form>
                            </div>
                        @endcan
                    @endauth
                </div>
            @endif
        @endforeach
    </div>
</div>
</x-plantilla-body>

</html>