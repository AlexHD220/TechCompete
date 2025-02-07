<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Administradores</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Cuentas Deshabilitadas (Administrador)</h1>            
            @if(0) <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" style="font-size: 14px;" onclick="window.location.href = '/administrador';">Regresar</button>
            @endif
        </div>

        @if($superadministradores->count() == 0 && $administradores->count() == 0)
            <p style="margin-left: 20px;"><i>No existen cuentas deshabilitadas.</i></p>
        @else
            @if($superadministradores->count() > 0)
                <div style="margin-bottom: 20px;">
                    <h3 style="margin-bottom: 15px;">SuperAdministradores deshabilitados</h3>

                    @foreach ($superadministradores as $superadministrador) <!--Listar todos los datos de la tabla user-->
                        @if($superadministrador->id != Auth::id() && $superadministrador->id != 1)
                            <li>
                                <b style="font-size: 20px;">{{ $superadministrador -> name }}</b>
                                
                                @if($superadministrador -> telefono)
                                    (<i>{{ $superadministrador -> email }}</i> | {{ $superadministrador -> telefono }})
                                @else
                                    (<i>{{ $superadministrador -> email }}</i>)
                                @endif
                                <h style="margin-right: 5px;"></h>    
                                
                                <form action="{{route('administrador.restore', $superadministrador->id)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea hablitar esta cuenta?')" onmouseover="this.style.backgroundColor='#63b38e';" onmouseout="this.style.backgroundColor='#198754';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #198754; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Habilitar Cuenta">
                                        <i class="fas fa-user-plus" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>
                                </form>

                                <form action="{{route('administrador.disabledharddestroy', $superadministrador->id)}}" method = "POST" style="display: inline-block;">
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
                <div>
                    <h3 style="margin-bottom: 15px;">Administradores deshabilitados</h3>

                    @foreach ($administradores as $administrador) <!--Listar todos los datos de la tabla user-->
                        @if($administrador->id != Auth::id() && $administrador->id != 1)
                            <li>
                                <b style="font-size: 20px;">{{ $administrador -> name }}</b>                                
                                
                                @if($administrador -> telefono)
                                    (<i>{{ $administrador -> email }}</i> | {{ $administrador -> telefono }})
                                @else
                                    (<i>{{ $administrador -> email }}</i>)
                                @endif
                                <h style="margin-right: 5px;"></h>    
                                
                                <form action="{{route('administrador.restore', $administrador->id)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea hablitar esta cuenta?')" onmouseover="this.style.backgroundColor='#63b38e';" onmouseout="this.style.backgroundColor='#198754';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #198754; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Habilitar Cuenta">
                                        <i class="fas fa-user-plus" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>
                                </form>

                                <form action="{{route('administrador.disabledharddestroy', $administrador->id)}}" method = "POST" style="display: inline-block;">
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