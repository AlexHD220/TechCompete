<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Administradores</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Administradores</h1>            
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" onclick="window.location.href = '/administrador/create';">Crear nueva cuenta</button>
            @endauth
        </div>

        @if($disabledsuperadministradores->count() > 0 || $disabledadministradores->count() > 0)
            <div style="margin-bottom: 20px;">
                <button onMouseOver="this.style.backgroundColor='#bd4e00'" onmouseout="this.style.backgroundColor='#e26b18'" class="btn btn-primary" style="font-size: 14px; background-color: #e26b18; border:0px; box-shadow: none;" onclick="window.location.href = '/administrador/trashed';"><b>Cuentas Deshabilitadas</b></button>
            </div>
        @endif

        @if($superadministradores->count() == 1 && $administradores->count() == 0)
            @if($disabledsuperadministradores->count() > 0 || $disabledadministradores->count() > 0)
                <p sty style="margin-left: 20px;"><i>Usted es el único Administrador activo.</i></p>
            @else
                <p sty style="margin-left: 20px;"><i>Usted es el único Administrador registrado.</i></p>
            @endif
        @else
            @if($superadministradores->count() > 1)
                <div style="margin-top: 15px;">
                    <h3>SuperAdministradores</h3>

                    @foreach ($superadministradores as $superadministrador) <!--Listar todos los datos de la tabla user-->
                        @if($superadministrador->id != Auth::id() && $superadministrador->id != 1)
                            <li>
                                <b style="font-size: 20px;">{{ $superadministrador -> name }}</b>
                                (<i><a href="mailto:{{ $superadministrador -> email }}">{{ $superadministrador -> email }}</a></i>)
                                <h style="margin-right: 5px;"></h>
                                
                                <!-- Botón para cambiar a Lower -->
                                <form action="{{ route('administrador.lower', $superadministrador) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" onclick="return confirm('¿Está seguro que desea disminuir los privilegios de esta cuenta?')" onmouseover="this.style.backgroundColor='#ffd24c';" onmouseout="this.style.backgroundColor='#f1b90e';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #f1b90e; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Disminuir Privilegios">
                                        <i class="fas fa-arrow-down" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>                                    
                                </form>

                                <form action="{{route('administrador.destroy', $superadministrador)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea deshabilitar esta cuenta?')" onmouseover="this.style.backgroundColor='#f97c3e';" onmouseout="this.style.backgroundColor='#ff5500';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #ff5500; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Deshabilitar Cuenta">
                                        <i class="fas fa-ban" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>
                                </form>
                                
                                <form action="{{route('administrador.harddestroy', $superadministrador)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta cuenta de forma permanente?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Eliminar Cuenta">
                                        <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>
                                </form>                                 
                            </li><br>
                        @endif
                    @endforeach
                </div>
            @endif

            @if($administradores->count() > 0)
                <div style="margin-top: 15px;">
                    <h3>Administradores</h3>

                    @foreach ($administradores as $administrador) <!--Listar todos los datos de la tabla user-->
                        @if($administrador->id != Auth::id() && $administrador->id != 1)
                            <li>
                                <b style="font-size: 20px;">{{ $administrador -> name }}</b>                                
                                (<i><a href="mailto:{{ $administrador -> email }}">{{ $administrador -> email }}</a></i>)
                                
                                <!-- Botón para cambiar a Upper -->
                                <form action="{{ route('administrador.upper', $administrador) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea aumentar los privilegios de esta cuenta?')" onmouseover="this.style.backgroundColor='#63b38e';" onmouseout="this.style.backgroundColor='#198754';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #198754; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Aumentar Privilegios">
                                        <i class="fas fa-arrow-up" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>                                    
                                </form>  

                                <form action="{{route('administrador.destroy', $administrador)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea deshabilitar esta cuenta?')" onmouseover="this.style.backgroundColor='#f97c3e';" onmouseout="this.style.backgroundColor='#ff5500';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #ff5500; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Deshabilitar Cuenta">
                                        <i class="fas fa-ban" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>
                                </form>
                                
                                <form action="{{route('administrador.harddestroy', $administrador)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta cuenta de forma permanente?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Eliminar Cuenta">
                                        <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>
                                </form>                                                              
                            </li><br>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
        <!--<br>
        <button onclick="window.location.href = '/administrador/create';">Registrar nuevo administrador</button>-->
    </div>
</x-plantilla-body>

</html>