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

        <div style="margin-bottom: 20px;">
            <button onMouseOver="this.style.backgroundColor='#141d76'" onmouseout="this.style.backgroundColor='#1b279c'" class="btn btn-primary" style="font-size: 14px; background-color: #1b279c; border:0px; box-shadow: none;" onclick="window.location.href = '/administrador/trashed';">Cuentas Deshabilitadas</button>
        </div>

        @if($superadministradores->count() == 1 && $administradores->count() == 0)
            <p sty style="margin-left: 20px;"><i>Usted es el único Administrador registrado.</i></p>
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
                                
                                <form action="{{route('administrador.destroy', $superadministrador)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea deshabilitar la cuenta?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Deshabilitar cuenta">
                                        <i class="fas fa-ban" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>
                                </form>
                                
                                <form action="{{route('administrador.harddestroy', $superadministrador)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta cuenta de forma permanente?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                    style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Eliminar cuenta">
                                        <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                    </button>
                                </form>

                                 <!-- Botón para cambiar a Lower -->
                                <form action="{{ route('administrador.lower', $superadministrador) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning">Lower</button>
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
                                |
                                <form action="{{route('administrador.destroy', $administrador)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea deshabilitar la cuenta?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  style="background-color: red; color: white;">
                                        Deshabilitar cuenta
                                    </button>
                                </form>
                                |
                                <form action="{{route('administrador.harddestroy', $administrador)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta cuenta de forma permanente?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  style="background-color: red; color: white;">
                                        Eliminar cuenta
                                    </button>
                                </form>

                                <!-- Botón para cambiar a Upper -->
                                <form action="{{ route('administrador.upper', $administrador) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Upper</button>
                                </form>

                                <button id="notificacion">Notificacion</button>
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