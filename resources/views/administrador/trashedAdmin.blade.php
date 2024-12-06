<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Administradores</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Cuentas Deshabilitadas (Administrador)</h1>            
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" style="font-size: 14px;" onclick="window.location.href = '/administrador';">Regresar</button>
            @endauth
        </div>

        @if($superadministradores->count() == 0 && $administradores->count() == 0)
            <p style="margin-left: 20px;"><i>No existen centas deshabilitadas.</i></p>
        @else
            @if($superadministradores->count() > 0)
                <div style="margin-bottom: 20px;">
                    <h3>SuperAdministradores deshabilitados</h3>

                    @foreach ($superadministradores as $superadministrador) <!--Listar todos los datos de la tabla user-->
                        @if($superadministrador->id != Auth::id() && $superadministrador->id != 1)
                            <li>
                                <b style="font-size: 20px;">{{ $superadministrador -> name }}</b>
                                (<i>{{ $superadministrador -> email }}</i>)
                                |
                                <form action="{{route('administrador.restore', $superadministrador->id)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea habilitar esta cuenta?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  style="background-color: red; color: white;">
                                        Habilitar cuenta
                                    </button>
                                </form>
                            </li><br>
                        @endif
                    @endforeach
                </div>
            @endif

            @if($administradores->count() > 0)
                <div>
                    <h3>Administradores deshabilitados</h3>

                    @foreach ($administradores as $administrador) <!--Listar todos los datos de la tabla user-->
                        @if($administrador->id != Auth::id() && $administrador->id != 1)
                            <li>
                                <b style="font-size: 20px;">{{ $administrador -> name }}</b>                                
                                (<i>{{ $administrador -> email }}</i>)
                                |
                                <form action="{{route('administrador.harddestroy', $administrador)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea habilitar esta cuenta?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  style="background-color: red; color: white;">
                                        Habilitar cuenta
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