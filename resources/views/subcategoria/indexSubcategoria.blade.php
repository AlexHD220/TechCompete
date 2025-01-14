<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Subcategoría</title>
</x-plantilla-head>

<x-plantilla-body>
    <div>
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Subcategorías</h1>            
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
                @can('only-superadmin')  
                <button class="btn btn-primary" id="btn-nueva-subcategoria">Registrar nueva subcategoría</button>
                @endcan
            @endauth
        </div>



        @if($subcategorias->count() == 0)
            <p sty style="margin-left: 20px; margin-bottom: 20px;"><i>Aún no se ha creado ninguna subcategoría.</i></p>

            <div style="margin-left: 20px;" >
        @else
            
            <div style="margin-top: 15px;">

                @foreach ($subcategorias as $subcategoria) <!--Listar todos los datos de la tabla user-->
                    
                    <li>                                                
                        <b style="font-size: 20px;">{{ $subcategoria -> nivel }}</b>     
                        <h style="margin-right: 5px;"></h>                   

                        <!-- Botón para Editar -->
                        <a href="{{ route('subcategoria.edit', $subcategoria) }}" onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                        style="margin-left: 5px; margin-right: 5px; background-color: #434851; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                        title="Editar Subcategoría">                                
                            <i class="fas fa-edit" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                        </a>    
                        
                        <form action="{{route('subcategoria.destroy', $subcategoria)}}" method = "POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')                                
                            
                            <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta subcategoría?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                            style="margin-left: 5px; margin-right: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                            title="Eliminar Subcategoría">
                                <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                            </button>
                        </form>                                
                    </li><br>
                    
                @endforeach                
            </div>            

            <div>
        @endif

            <form action="/subcategoria" method="post"> <!--la diagonal me envia al principio de la url "techcompete.test/"-->
                <!--Mostrar errores-->
                @if ($errors->any())
                    <div class="msgAlerta">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <br>
                @endif

                @csrf <!--permite entrar al formulario muy importante agregar-->
                
                <div style="display: flex; flex-wrap: wrap; align-items: center;">
                    <input type="text" id="nivel" name="nivel" style="width: 250px;" placeholder="Nueva Subcategoría" required value = "{{ old('nivel') }}"><!--value = "{{old('nivel')}}"-->

                    <input type="submit" id="submitButton" value="Registrar Subcategoría" style="margin-left: 15px;" disabled> 
                    <!--<a href="{{ route('subcategoria.create') }}" style="margin-left:10px;">Cancelar</a>-->
                </div>
            </form>   
            <br>         
        </div>

        <!--<br>
        <button onclick="window.location.href = '/subcategoria/create';">Registrar nuevo subcategoria</button>-->
    </div>

    <!--<script>
        // Desplázarse al fondo de la página por defecto
        document.addEventListener('DOMContentLoaded', function() {
            window.scrollTo(0, document.body.scrollHeight);
        });
    </script>-->

    <!-- Script -->
    <script>
        document.getElementById('btn-nueva-subcategoria').addEventListener('click', function(event) {            
            
            // Desplázate al fondo de la página
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth' // Movimiento suave
            });

            // Establece el enfoque en el input
            const input = document.getElementById('nivel');
            if (input) {
                input.focus();
            }
        });
    </script>

    <script>
        document.getElementById('nivel').addEventListener('input', function() {
            var submitButton = document.getElementById('submitButton');
            if (this.value.trim() !== '') {
                submitButton.removeAttribute('disabled');
            } else {
                submitButton.setAttribute('disabled', 'true');
            }
        });
    </script>
</x-plantilla-body>

</html>