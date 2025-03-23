<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Asesor</title>
</x-plantilla-head>

<x-plantilla-body>
    <div>
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Cuentas Pendientes de Aprobación</h1>
        </div>

        @if($cuentasAsesorescount == 0)                                        
            <p sty style="margin-left: 20px;"><i>Actualmente no hay cuentas de asesores pendientes de aprobación.</i></p>            
        @else
            
            <div style="margin-top: 15px;">

                @foreach ($cuentasAsesores as $cuentaAsesor) <!--Listar todos los datos de la tabla user-->
                    
                    <li>                            

                        @if(0)
                        <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('asesor.showvalidarcuenta', $cuentaAsesor)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                            <b style="font-size: 20px;">{{ $cuentaAsesor -> name }}</b>
                        </a>
                        @endif

                        <b style="font-size: 20px;">{{ $cuentaAsesor -> name }} {{ $cuentaAsesor -> lastname }}</b>
                        
                        @if($cuentaAsesor -> telefono)
                            (<i><a target="_blank" href="mailto:{{ $cuentaAsesor -> email }}">{{ $cuentaAsesor -> email }}</a></i> | <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $cuentaAsesor -> telefono }}">{{ $cuentaAsesor -> telefono }}</a>)
                        @else
                            (<i><a target="_blank" href="mailto:{{ $cuentaAsesor -> email }}">{{ $cuentaAsesor -> email }}</a></i>)
                        @endif
                        <h style="margin-right: 5px;"></h>
                        
                        <!-- Botón para Editar -->
                        <a href="{{route('asesor.showvalidarcuenta', $cuentaAsesor)}}" onmouseover="this.style.backgroundColor='#203454';" onmouseout="this.style.backgroundColor='#081c44';" 
                        style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #081c44; color: white; border: none; padding: 5px 10px; border-radius: 5px; display: inline-flex; justify-content: center; align-items: center;"
                        title="Revisar Información de la Cuenta">                                
                            <i class="fa-regular fa-id-card" style="font-size: 15px; margin-right: 5px;"></i> <!-- Ícono de FontAwesome -->
                            Revisar Cuenta
                        </a>                        

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

</html>