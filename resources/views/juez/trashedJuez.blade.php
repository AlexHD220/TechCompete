<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Jueces</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Cuentas Deshabilitadas (Juez)</h1>            
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" style="font-size: 14px;" onclick="window.location.href = '/juez';">Regresar</button>
            @endauth
        </div>

        @if($jueces->count() == 0)
            <p style="margin-left: 20px;"><i>No existen cuentas deshabilitadas.</i></p>
        @else

            <div style="margin-bottom: 20px;">                

                @foreach ($jueces as $juez) <!--Listar todos los datos de la tabla user-->
                    
                    <li>
                        <b style="font-size: 20px;">{{ $juez -> name }}</b>
                        (<i>{{ $juez -> email }}</i>)
                        
                        <form action="{{route('juez.restore', $juez->id)}}" method = "POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            
                            <button type="submit" onclick="return confirm('¿Está seguro que desea hablitar esta cuenta?')" onmouseover="this.style.backgroundColor='#63b38e';" onmouseout="this.style.backgroundColor='#198754';"  
                            style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #198754; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                            title="Habilitar Cuenta">
                                <i class="fas fa-user-plus" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                            </button>
                        </form>

                        <form action="{{route('juez.disabledharddestroy', $juez->id)}}" method = "POST" style="display: inline-block;">
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
        <!--<br>-->
    </div>
</x-plantilla-body>

</html>