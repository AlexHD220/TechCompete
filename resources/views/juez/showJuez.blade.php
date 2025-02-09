<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Juez | Mostrar</title>

    <!-- Estilos -->
    <style>
        /* Estilo para el modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Imagen sin márgenes, centrada */
        .modal-image {
            height: 500px; /* Máxima altura de la imagen */
            object-fit: contain; /* Ajustar imagen sin recortes */
            max-width: 100%; /* Asegura que no se desborde horizontalmente */
            margin: 0; /* Sin márgenes adicionales */
            padding: 0;
        }

        /* Fondo difuminado */
        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px); /* Difumina el fondo */
            z-index: -1;
        }

        /* Botón para cerrar */
        .close {
            position: absolute;
            top: 5px;
            right: 25px;
            font-size: 45px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            z-index: 1001; /* Asegura que esté encima de la imagen */
        }

        /* Quitar scroll al mostrar el modal */
        body.modal-open {
            overflow: hidden;
        }
    </style>
</x-plantilla-head>

<x-plantilla-body>

    <!--<h2> {{ $juez -> usuario }}</h2>--> <!--Mostrar detalles-->

    @if (!is_null($juez->user->profile_photo_path))
        <!-- Modal -->
        <div id="imageModal" class="modal" style="display: none;">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-overlay" onclick="closeModal()"></div>
            <img src="{{ $juez->user->profile_photo_url }}" alt="{{ $juez->user->name }}" class="modal-image">
        </div>
    @endif

    <div style="display: flex; align-items: center; margin-bottom: 20px;">
        
        <!-- Foto de perfil (solo si existe, sino genera imagen de color con inicial) -->
        <!--@if ($juez->user->profile_photo_url !== 0)
        @endif-->

        <!-- Imagen de perfil -->
        @if (!is_null($juez->user->profile_photo_path))
            <img src="{{ $juez->user->profile_photo_url }}" alt="{{ $juez->user->name }}"style="height: 100px; width: 100px; margin-right: 15px; border-radius: 10px; object-fit: cover; cursor: pointer;" onclick="showModal()">
        @endif
        
        
        <!-- Contenedor de nombre y apellido -->
        <div>
            <h1 style="margin-top: 8px; margin-bottom: 10px; margin-right: 15px;"> {{ $juez -> name }}</h1>
            <h1 style="margin-right: 15px;"> {{ $juez -> lastname }}</h1>
        </div>
    </div>

    <h4> Correo electronico: </h4>
    <p style="margin-left: 15px; margin-bottom: 20px; font-size: 18px;"> <a target="_blank" href="mailto:{{ $juez -> email }}">{{ $juez -> email }}</a> </p>

    @if (!empty($juez->telefono))
        <h4> Teléfono: </h4>
        <p style="margin-left: 15px; margin-bottom: 20px; font-size: 18px;"> {{ $juez -> telefono }} </p>
    @endif

    <!--@if (!empty($juez->escuela))
        <h3> Escuela: {{ $juez->escuela }}</h3>
    @endif-->

    @if ($juez->user->competencias->count() > 0)
        <br>
        <h3>Competencias</h3>

        <ul>
            @foreach($juez->competencias as $competencia)
                <li>
                    <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('competencia.show', $competencia)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                        {{ $competencia -> nombre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    

</x-plantilla-body>

<!-- Scripts -->
<script>
    // Mostrar el modal
    function showModal() {
        document.getElementById('imageModal').style.display = 'flex';
        document.body.classList.add('modal-open'); // Deshabilita el scroll
    }

    // Cerrar el modal
    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.body.classList.remove('modal-open'); // Reactiva el scroll
    }
</script>

</html>