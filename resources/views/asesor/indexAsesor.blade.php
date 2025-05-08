<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Asesor</title>

    <style>

        /* Badge de notificaciones */
        .notification-badge {
            position: absolute;
            top: 85px;       /* Ajusta la posición vertical (-5px) */
            right: 15px;    /* Ajusta la posición horizontal (-5px) */
            background-color: red;
            color: white;
            border-radius: 50%;

            /*padding: 2px 10px; /* Tamaño y forma circular */
            /*font-size: 20px;
            font-weight: bold;
            /*pointer-events: none; /* Permite hacer clic en el botón sin interferencia */

            min-width: 25px; /* Tamaño mínimo del círculo */
            height: 25px; /* Mantiene la altura constante */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            padding: 3px;
            line-height: 1;
            pointer-events: none;
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

    <div>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
            <h1 style="display: inline;">Listado de Asesores</h1>
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->                

                <div class="button-container">
                    <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
                    onclick="window.location.href = '/asesor/solicitud';"><b>Solicitudes de asesores</b></button>                    
                    
                    @if($solicitudesAsesorescount > 0)
                        <span class="notification-badge"><b>{{$solicitudesAsesorescount}}</b></span>
                    @endif
                </div>
                        
            @endauth
        </div>

        @if($asesorescount == 0)                                        
            <p sty style="margin-left: 20px;"><i>Actualmente no hay cuentas de asesores pendientes de aprobación.</i></p>            
        @else
            
            <div style="margin-top: 15px;">

                @foreach ($asesores as $asesor) <!--Listar todos los datos de la tabla user-->
                    
                    <li>                            

                        @if(0)
                        <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('asesor.showvalidarcuenta', $asesor)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                            <b style="font-size: 20px;">{{ $asesor -> name }}</b>
                        </a>
                        @endif

                        <b style="font-size: 20px;">{{ $asesor -> name }} {{ $asesor -> lastname }}</b>
                        
                        @if($asesor -> telefono)
                            (<i><a target="_blank" href="mailto:{{ $asesor -> email }}">{{ $asesor -> email }}</a></i> | <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $asesor -> telefono }}">{{ $asesor -> telefono }}</a>)
                        @else
                            (<i><a target="_blank" href="mailto:{{ $asesor -> email }}">{{ $asesor -> email }}</a></i>)
                        @endif
                        <h style="margin-right: 5px;"></h>
                                                                      
                    </li><br>
                    
                @endforeach
            </div>
            

        @endif
        <!--<br>
        <button onclick="window.location.href = '/juez/create';">Registrar nuevo juez</button>-->
    </div>
</x-plantilla-body>

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