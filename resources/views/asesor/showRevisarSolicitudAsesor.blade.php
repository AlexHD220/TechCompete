<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Asesor | Revisar Cuenta</title>

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

    <style>
        .sombra {
            /*box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.5);*/
            box-shadow: 0px 0px 5px 3px rgba(255,255,255,0.2);
        }
    </style>
</x-plantilla-head>

<x-plantilla-body>

    @if (session('alerta'))
        <script>                
            document.addEventListener('DOMContentLoaded', function () {            
                    // Captura los datos de la sesión y llama a la función                        
                    sweetAlertNotificationHTML("{{ session('alerta.titulo') }}", "{{ session('alerta.texto') }}",  "{!! session('alerta.html') !!}", "{{ session('alerta.icono') }}", "{{ session('alerta.tiempo') }}", "{{ session('alerta.botonConfirmacion') }}");
            });
        </script>
    @endif

    <!-- Modal Imagen -->
    <div id="imageModal" class="modal" style="display: none;">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-overlay" onclick="closeModal()"></div>
        <img src="{{ \Storage::url($asesor->identificacion_path) }}" alt="{{ $asesor->name }}" class="modal-image" style="width: 90%;">
    </div>

    <!--<div style="display: flex; align-items: center; margin-bottom: 20px;">-->
    <div class="text-center" style="margin-bottom: 30px;">

        <!-- Imagen de portada -->        
        <img class="sombra" src="{{ \Storage::url($asesor->identificacion_path) }}" alt="{{ $asesor->name }}" style="width: 50%; max-height: 500px; border-radius: 15px; object-fit: cover; cursor: pointer;" onclick="showModal()">        
                    
    </div>    
    
    
    <div class="d-flex justify-content-between align-items-center" style="width: 100%; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 25px; justify-content: center;">
        <div>
            <!-- Contenedor de nombre y apellido -->    
            <h1 style="margin-top: 8px; margin-bottom: 10px; margin-right: 15px;"> {{ $asesor -> name }}</h1>
            <h1 style="margin-right: 15px;"> {{ $asesor -> lastname }}</h1>   
        </div>           
        
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">

            <!-- Botón para cambiar a Upper -->
            <form action="{{ route('asesor.aprobarcuenta', $asesor) }}" method="POST" style="display: inline;">
                @csrf
                @method('POST')

                <!-- Campos ocultos con la información de los asesores -->
                <input type="hidden" name="asesor_anterior_id" value="{{ $asesorAnterior ? $asesorAnterior->id : $asesorAnterior }}">
                <input type="hidden" name="asesor_siguiente_id" value="{{ $asesorSiguiente ? $asesorSiguiente->id : $asesorSiguiente }}">
                                
                <button class="btn btn-primary"  type="submit" onclick="return confirm('¿Está seguro que desea aprobar esta cuenta de usuario?')" onMouseOver="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'" 
                style="font-size: 14px; background-color: #28a745; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
                title="Aprobar Cuenta de Asesor"><b>Aprobar Cuenta</b></button>
            </form> 

            <button id="rechazarCuentaBtn" class="btn btn-primary" onMouseOver="this.style.backgroundColor='#c82333'" onmouseout="this.style.backgroundColor='#dc3545'" 
            style="font-size: 14px; background-color: #dc3545; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
            data-link="{{ route('asesor.rechazarcuenta', $asesor) }}">
            <b>Rechazar Cuenta</b></button>             

        </div>
    </div>


    <h4> Correo electronico: </h4>
    <p style="margin-left: 15px; margin-bottom: 20px; font-size: 18px;"> <a target="_blank" href="mailto:{{ $asesor -> email }}">{{ $asesor -> email }}</a> </p>

    @if (!empty($asesor->telefono))
        <h4> Teléfono: </h4>
        <p style="margin-left: 15px; margin-bottom: 20px; font-size: 18px;"> {{ $asesor -> telefono }} </p>
    @endif    

    <div class="d-flex justify-content-between align-items-center" style="width: 100%; margin-top: 30px; margin-bottom: 35px; display: flex; flex-wrap: wrap; gap: 25px; justify-content: center;"> <!-- gap: 15px; -->
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;"> <!-- gap: 15px; -->
            @if($asesorAnterior)
            <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
            link="{{ route('asesor.showvalidarcuenta', $asesorAnterior) }}" 
            onclick="window.location.href = this.getAttribute('link');"><b>Cuenta anterior</b></button>
            @endif

            @if($asesorSiguiente)
            <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
            link="{{ route('asesor.showvalidarcuenta', $asesorSiguiente) }}" 
            onclick="window.location.href = this.getAttribute('link');"><b>Siguiente cuenta</b></button>
            @endif
        </div>           
        
        <div>
            <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#c24e0b'" onmouseout="this.style.backgroundColor='#f96510'" style="font-size: 14px; background-color: #f96510; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;"             
            onclick="window.location.href='/asesor/validarcuenta';"><b>Regresar</b></button>
        </div>
        
    </div>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('rechazarCuentaBtn').addEventListener('click', async function(e) {
        e.preventDefault(); // Prevenir la acción predeterminada

        const route = this.getAttribute('data-link'); // Obtener la URL

        const { value: observaciones } = await Swal.fire({
            title: 'Observaciones',
            text: 'Agrega las observaciones para rechazar la cuenta:',
            input: 'textarea',
            inputPlaceholder: 'Escribe tus observaciones aquí...',
            inputAttributes: {
                'aria-label': 'Escribe tus observaciones'
            },
            showCancelButton: true,
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value || value.length < 10) {
                    return 'Debes escribir tus observaciones antes de rechazar la cuenta.';
                }
            }
        });

        if (observaciones) {
            // Crea un formulario oculto para enviar los datos vía POST
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = route;

            // Agrega el token CSRF (requerido para Laravel)
            let token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);

            // Agrega el campo observaciones
            let inputObservaciones = document.createElement('input');
            inputObservaciones.type = 'hidden';
            inputObservaciones.name = 'observaciones';
            inputObservaciones.value = observaciones;
            form.appendChild(inputObservaciones);

            // Agrega los IDs de los asesores con una comprobación para null
            let inputAsesorAnterior = document.createElement('input');
            inputAsesorAnterior.type = 'hidden';
            inputAsesorAnterior.name = 'asesor_anterior_id';  
            inputAsesorAnterior.value = '{{ $asesorAnterior ? $asesorAnterior->id : $asesorAnterior }}'; // Si $asesorAnterior es null, asignar 0
            form.appendChild(inputAsesorAnterior);

            let inputAsesorSiguiente = document.createElement('input');
            inputAsesorSiguiente.type = 'hidden';
            inputAsesorSiguiente.name = 'asesor_siguiente_id';  
            inputAsesorSiguiente.value = '{{ $asesorSiguiente ? $asesorSiguiente->id : $asesorSiguiente }}'; // Si $asesorSiguiente es null, asignar 0
            form.appendChild(inputAsesorSiguiente);

            // Agrega el formulario al body y lo envía
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>

<script>
    function sweetAlertNotificationHTML(titulo, texto, html, icono, tiempo, botonConfirmacion) {  
    
        // Usando SweetAlert para notificación
        Swal.fire({
            title: titulo,
            text: texto,
            html: html,
            icon: icono,
            timer: tiempo,
            showConfirmButton: botonConfirmacion
        });
    }
</script>


</html>