<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Staffs</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Staffs</h1>            
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" onclick="window.location.href = '/staff/create';">Crear nueva cuenta</button>
            @endauth
        </div>

        @if($disabledsuperstaffs->count() > 0 || $disabledstaffs->count() > 0)
            <div style="margin-bottom: 20px;">
                <button onMouseOver="this.style.backgroundColor='#bd4e00'" onmouseout="this.style.backgroundColor='#e26b18'" class="btn btn-primary" style="font-size: 14px; background-color: #e26b18; border:0px; box-shadow: none;" onclick="window.location.href = '/staff/trashed';"><b>Cuentas Deshabilitadas</b></button>
            </div>
        @endif

        @if($superstaffs->count() == 0 && $staffs->count() == 0)
            @if($disabledsuperstaffs->count() > 0 || $disabledstaffs->count() > 0)
                <p sty style="margin-left: 20px;"><i>No existen cuentas activas.</i></p>
            @else
                <p sty style="margin-left: 20px;"><i>Aún no se ha creado ninguna cuenta.</i></p>
            @endif
        @else
            @if($superstaffs->count() > 0)
                <div style="margin-top: 15px;">
                    <h3 style="margin-bottom: 15px;">SuperStaffs</h3>

                    @foreach ($superstaffs as $superstaff) <!--Listar todos los datos de la tabla user-->

                        <li>
                            <b style="font-size: 20px;">{{ $superstaff -> name }}</b>
                            
                            @if($superstaff -> telefono)
                                (<i><a href="mailto:{{ $superstaff -> email }}">{{ $superstaff -> email }}</a></i> | <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $superstaff -> telefono }}">{{ $superstaff -> telefono }}</a>)
                            @else
                                (<i><a href="mailto:{{ $superstaff -> email }}">{{ $superstaff -> email }}</a></i>)
                            @endif
                            <h style="margin-right: 5px;"></h>
                            
                            <!-- Botón para cambiar a Lower -->
                            <form action="{{ route('staff.lower', $superstaff) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')

                                <button type="submit" onclick="return confirm('¿Está seguro que desea disminuir los privilegios de esta cuenta?')" onmouseover="this.style.backgroundColor='#ffd24c';" onmouseout="this.style.backgroundColor='#f1b90e';"  
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #f1b90e; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Disminuir Privilegios">
                                    <i class="fas fa-arrow-down" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>                                    
                            </form>

                            <form action="{{route('staff.destroy', $superstaff)}}" method = "POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" onclick="return confirm('¿Está seguro que desea deshabilitar esta cuenta?')" onmouseover="this.style.backgroundColor='#f97c3e';" onmouseout="this.style.backgroundColor='#ff5500';"  
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #ff5500; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Deshabilitar Cuenta">
                                    <i class="fas fa-ban" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>
                            </form>
                            
                            <form action="{{route('staff.harddestroy', $superstaff)}}" method = "POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta cuenta de forma permanente?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Eliminar Cuenta">
                                    <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>
                            </form>                                 
                        </li><br>
                        
                    @endforeach
                </div>
            @endif

            @if($staffs->count() > 0)
                <div style="margin-top: 20px;">
                    <h3 style="margin-bottom: 15px;">Staffs</h3>

                    @foreach ($staffs as $staff) <!--Listar todos los datos de la tabla user-->
                        
                        <li>
                            <b style="font-size: 20px;">{{ $staff -> name }}</b>                                
                            
                            @if($staff -> telefono)
                                (<i><a href="mailto:{{ $staff -> email }}">{{ $staff -> email }}</a></i> | <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $staff -> telefono }}">{{ $staff -> telefono }}</a>)
                            @else
                                (<i><a href="mailto:{{ $staff -> email }}">{{ $staff -> email }}</a></i>)
                            @endif
                            <h style="margin-right: 5px;"></h>
                            
                            <!-- Botón para cambiar a Upper -->
                            <form action="{{ route('staff.upper', $staff) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                
                                <button type="submit" onclick="return confirm('¿Está seguro que desea aumentar los privilegios de esta cuenta?')" onmouseover="this.style.backgroundColor='#63b38e';" onmouseout="this.style.backgroundColor='#198754';"  
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #198754; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Aumentar Privilegios">
                                    <i class="fas fa-arrow-up" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>                                    
                            </form>  

                            <form action="{{route('staff.destroy', $staff)}}" method = "POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" onclick="return confirm('¿Está seguro que desea deshabilitar esta cuenta?')" onmouseover="this.style.backgroundColor='#f97c3e';" onmouseout="this.style.backgroundColor='#ff5500';"  
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #ff5500; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Deshabilitar Cuenta">
                                    <i class="fas fa-ban" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>
                            </form>
                            
                            <form action="{{route('staff.harddestroy', $staff)}}" method = "POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta cuenta de forma permanente?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Eliminar Cuenta">
                                    <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>
                            </form>                                                              
                        </li><br>
                        
                    @endforeach
                </div>
            @endif
        @endif
        <!--<br>
        <button onclick="window.location.href = '/staff/create';">Registrar nuevo staff</button>-->
    </div>
</x-plantilla-body>

</html>