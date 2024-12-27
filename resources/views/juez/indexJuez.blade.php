<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Jueces</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Jueces</h1>            
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" onclick="window.location.href = '/registrojuez';">Códigos de registro</button>
            @endauth
        </div>

        @if($disabledjueces->count() > 0)
            <div style="margin-bottom: 20px;">
                <button onMouseOver="this.style.backgroundColor='#bd4e00'" onmouseout="this.style.backgroundColor='#e26b18'" class="btn btn-primary" style="font-size: 14px; background-color: #e26b18; border:0px; box-shadow: none;" onclick="window.location.href = '/juez/trashed';"><b>Cuentas Deshabilitadas</b></button>
            </div>
        @endif

        @if($jueces->count() == 0)
            @if($disabledjueces->count() > 0)
                <p sty style="margin-left: 20px;"><i>No existen cuentas activas.</i></p>
            @else
                <p sty style="margin-left: 20px;"><i>Aún no se ha creado ninguna cuenta.</i></p>
            @endif
        @else
            
            <div style="margin-top: 15px;">

                @foreach ($jueces as $juez) <!--Listar todos los datos de la tabla user-->
                    
                    <li>                            

                        <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('juez.show', $juez)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                            <b style="font-size: 20px;">{{ $juez -> name }}</b>
                        </a>

                        (<i><a href="mailto:{{ $juez -> email }}">{{ $juez -> email }}</a></i>)
                        <h style="margin-right: 5px;"></h>

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
                    </li><br>
                    
                @endforeach
            </div>
            

        @endif
        <!--<br>
        <button onclick="window.location.href = '/juez/create';">Registrar nuevo juez</button>-->
    </div>
</x-plantilla-body>

</html>