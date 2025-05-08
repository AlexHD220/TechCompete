<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Asesor</title>
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
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Solicitudes de Asesores</h1>
        </div>

        @if($solicitudesAsesoresCount == 0)                                        
            <p sty style="margin-left: 20px;"><i>Actualmente no hay cuentas de asesores pendientes de aprobación.</i></p>            
        @else
            
            <div style="margin-top: 15px;">

                @foreach ($cuentasAsesores as $asesor) <!--Listar todos los datos de la tabla user-->
                    
                    <li>                            
                        
                        <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('asesor.showvalidarcuenta', $asesor)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                            <b style="font-size: 20px;">{{ $asesor -> name }} {{ $asesor -> lastname }}</b>
                        </a>                        

                        @if(0)
                        <b style="font-size: 20px;">{{ $asesor -> name }} {{ $asesor -> lastname }}</b>
                        @endif

                        @if($asesor -> telefono)
                            (<i><a target="_blank" href="mailto:{{ $asesor -> email }}">{{ $asesor -> email }}</a></i> | <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $asesor -> telefono }}">{{ $asesor -> telefono }}</a>)
                        @else
                            (<i><a target="_blank" href="mailto:{{ $asesor -> email }}">{{ $asesor -> email }}</a></i>)
                        @endif
                        <h style="margin-right: 5px;"></h>
                        
                        @if(0)
                        <!-- Botón para Editar -->
                        <a href="{{route('asesor.showsolicitudasesores', $asesor)}}" onmouseover="this.style.backgroundColor='#203454';" onmouseout="this.style.backgroundColor='#081c44';" 
                        style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #081c44; color: white; border: none; padding: 5px 10px; border-radius: 5px; display: inline-flex; justify-content: center; align-items: center;"
                        title="Revisar Información de la Cuenta">                                
                            <i class="fa-regular fa-id-card" style="font-size: 15px; margin-right: 5px;"></i> <!-- Ícono de FontAwesome -->
                            Revisar Cuenta
                        </a> 
                        @endif                                                                            
                        
                            @if(0)
                            <a href="{{route('asesor.showsolicitudasesores', $asesor)}}" onmouseover="this.style.backgroundColor='#203454';" onmouseout="this.style.backgroundColor='#081c44';" 
                            style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #081c44; color: white; border: none; padding: 12px 12px; border-radius: 5px; display: inline-flex; justify-content: center; align-items: center;"
                            title="Revisar Información de la Cuenta">                                
                                <i class="fa-solid fa-eye" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->                                
                            </a> 
                            @endif

                            <!-- Botón para cambiar a Upper -->
                            <form action="{{ route('asesor.aprobarsolicitud', $asesor) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')

                                <!-- Campos ocultos con la información de los asesores -->
                                <input type="hidden" name="listado" value="true">
                                                
                                <button class="btn btn-primary"  type="submit" onclick="return confirm('¿Está seguro que desea aceptar la solicitud de este asesor?')" onMouseOver="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'" 
                                style="font-size: 14px; background-color: #28a745; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px; margin-left: 5px; margin-right: 5px;" 
                                title="Aprobar Cuenta de Asesor"><b>Aceptar </b></button>
                            </form>                             
                            
                            <!-- Botón para cambiar a Upper -->
                            <form action="{{ route('asesor.rechazarsolicitud', $asesor) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')

                                <!-- Campos ocultos con la información de los asesores -->
                                <input type="hidden" name="listado" value="true">

                                <button class="btn btn-primary" onclick="return confirm('¿Está seguro que desea rechazar la solicitud de este asesor?')" onMouseOver="this.style.backgroundColor='#c82333'" onmouseout="this.style.backgroundColor='#dc3545'" 
                                style="font-size: 14px; background-color: #dc3545; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px; margin-left: 5px; margin-right: 5px;" 
                                title="recgazar solicitud de Asesor"><b>Rechazar</b></button>  
                            </form>
                        

                        @if(0)
                            <form action="{{route('juez.destroy', $juez)}}" method = "POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" onclick="return confirm('¿Está seguro que desea deshabilitar esta cuenta?')" onmouseover="this.style.backgroundColor='#f97c3e';" onmouseout="this.style.backgroundColor='#ff5500';"  
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #ff5500; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Deshabilitar Cuenta">
                                    <i class="fas fa-ban" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>
                            </form>
                            
                            <form action="{{route('juez.harddestroy', $juez)}}" method = "POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta cuenta de forma permanente?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Eliminar Cuenta">
                                    <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>
                            </form>  
                        @endif                               
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