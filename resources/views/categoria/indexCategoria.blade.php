<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Categorías</title>
</x-plantilla-head>

<x-plantilla-body>
    <div>
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Categorías</h1>            
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
                @can('only-superadmin')   
                    <div style="display: flex; flex-wrap: wrap; gap: 15px 20px;">     
                        <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
                        onclick="window.location.href = '/subcategoria';"><b>Subcategorias</b></button> <!-- Ruta pendiente --> 

                        <button class="btn btn-primary" onclick="window.location.href = '/categoria/create';">Registrar nueva categoría</button>            
                    </div>      
                @endcan
            @endauth
        </div>

        @if($disabledcategoriascount > 0)
            <div style="margin-bottom: 20px;">
                <button onMouseOver="this.style.backgroundColor='#bd4e00'" onmouseout="this.style.backgroundColor='#e26b18'" class="btn btn-primary" style="font-size: 14px; background-color: #e26b18; border:0px; box-shadow: none;" onclick="window.location.href = '/categoria/trashed';"><b>Categorías eliminadas</b></button>
            </div>
        @endif

        @if($categorias->count() == 0)
            @if($disabledcategoriascount > 0)
                <p sty style="margin-left: 20px;"><i>Actualmente no hay ningúna categoría.</i></p>
            @else
                <p sty style="margin-left: 20px;"><i>Aún no se ha creado ninguna categoría.</i></p>
            @endif
        @else
            
            <div style="margin-top: 15px;">

                @foreach ($categorias as $categoria) <!--Listar todos los datos de la tabla categorias -->
                    
                    <div style="display: inline-block; margin-bottom: 5px;">
                        <li>
                            <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('categoria.show', $categoria)}}" style="text-decoration: none; color: inherit;">
                                <b style="font-size: 20px;"> {{ $categoria -> name }} </b>
                            </a>
                            
                            (<h title="Tipo de categoría">{{ $categoria -> tipo }}</h>)                            
                            <h style="margin-right: 5px;"></h>
                                                                    
                            <!-- Botón para Editar -->
                            <a href="{{ route('categoria.edit', $categoria) }}" onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                            style="margin-left: 5px; margin-right: 5px; background-color: #434851; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                            title="Editar Categoría">                                
                                <i class="fas fa-edit" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                            </a>    
                            
                            <form action="{{route('categoria.destroy', $categoria)}}" method = "POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta categoría?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                style="margin-left: 5px; margin-right: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                title="Eliminar Categoría">
                                    <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                                </button>
                            </form>  
                        </li>                     
                    </div> 
                         
                    <p style="text-align: justify; max-width: 90%; margin-left: 20px; margin-bottom: 0px;">{{ $categoria -> descripcion}}</p>
                    <br>
                    
                @endforeach
            </div>
            

        @endif
        <!--<br>
        <button onclick="window.location.href = '/categoria/create';">Registrar nuevo categoria</button>-->
    </div>
</x-plantilla-body>

</html>