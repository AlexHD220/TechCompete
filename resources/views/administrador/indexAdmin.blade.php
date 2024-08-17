<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Administradores</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
            <h1 style="display: inline;">Listado de Administradores</h1>
            <!--<button class="btn btn-primary" onclick="window.location.href = '/administrador/create';">Registrar nuevo administrador</button>-->
        </div>

        @if ($administradores->count() == 1)
            <p style="margin-left: 20px;"><i>Usted es el único administrador registrado.</i></p>
        @endif


        @foreach ($administradores as $administrador) <!--Listar todos los datos de la tabla administradores-->
            @if($administrador->id != Auth::id() && $administrador->id != 1)
                <li>
                    
                    <b style="font-size: 20px;">{{ $administrador -> name }}</b>

                    (<i>{{ $administrador -> email }}</i>)

                    |
                    
                    <form action="{{route('administrador.destroy', $administrador)}}" method = "POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" onclick="return confirm('¿Está seguro que desea deshabilitar la cuenta de este administrador?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  style="background-color: red; color: white;">
                            Deshabilitar cuenta
                        </button>

                    </form>
                </li><br>
            @endif
        @endforeach

        <!--<br>
        <button onclick="window.location.href = '/administrador/create';">Registrar nuevo administrador</button>-->
    </div>
</x-plantilla-body>

</html>