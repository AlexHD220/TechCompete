<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Categorias Eliminadas</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Categorías Eliminadas</h1>            
            @if(0) <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" style="font-size: 14px;" onclick="window.location.href = '/categoria';">Regresar</button>
            @endif
        </div>

        @if($categorias->count() == 0)
            <p style="margin-left: 20px;"><i>No existen categorías eliminadas.</i></p>
        @else

            <div style="margin-bottom: 20px;">                

                @foreach ($categorias as $categoria) <!--Listar todos los datos de la tabla user-->
                        
                    <div style="display: inline-block; margin-bottom: 5px;">
                        <li>
                            
                            <b style="font-size: 20px;;"> {{ $categoria -> name }} </b>
                            
                            
                            <h style="opacity: 0.6" title="Tipo de categoría">({{ $categoria -> tipo }})</h>
                            <h style="margin-right: 5px;"></h>  
                            
                            <form action="{{route('categoria.restore', $categoria->id)}}" method = "POST" style="display: inline-block;">
                                @csrf
                                @method('PATCH')
                                
                                <button type="submit" onclick="return confirm('¿Está seguro que desea restaurar esta categoría?')" onmouseover="this.style.backgroundColor='#63b38e';" onmouseout="this.style.backgroundColor='#198754';"  
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #198754; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Restaurar Categoría">
                                    <i class="fa fa-refresh" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>
                            </form>
                        </li>       
                    </div>              
                    
                    <p style="text-align: justify; max-width: 90%; margin-left: 20px; margin-bottom: 0px; opacity: 0.6;">{{ $categoria -> descripcion}}</p>
                    <br>
                    
                @endforeach
            </div>
        
        @endif
        <!--<br>-->
    </div>
</x-plantilla-body>

</html>