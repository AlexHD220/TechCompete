<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Editar Subcategoría</title>
</x-plantilla-head>

<x-plantilla-body>
    <div>
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Subcategorías (Editar)</h1>            
            @if(0) <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" onclick="window.location.href = '/subcategoria';">Códigos de registro</button>
            @endif
        </div>

            
        <div style="margin-top: 15px;">

            @foreach ($subcategorias as $subcategoriaone) <!--Listar todos los datos de la tabla user-->
                
                <li>                                                
                    <b style="font-size: 20px;">{{ $subcategoriaone -> nivel }}</b>                                                        
                </li><br>
                
            @endforeach                
        </div>            

        <div>                
            <form action="{{ route('subcategoria.update', $subcategoria)}}" method="post"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->
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
                @method ('PATCH') <!--permite truquear nuestro formulario para editar la informacion-->
                
                <div style="display: flex; flex-wrap: wrap; align-items: center;">
                    <input type="text" id="nivel" name="nivel" style="width: 250px;" placeholder="Editar Subcategoría" required value = "{{ old('nivel') ?? $subcategoria -> nivel }}" autofocus><br><br> <!--value = "{{old('nivel')}}"-->

                    <input type="submit" id="submitButton" value="Actualizar Subcategoría" style="margin-left: 15px;"> 
                    <a href="{{ route('subcategoria.create') }}" style="margin-left:10px;">Cancelar</a>
                </div>
            </form>
            <br>
        </div>

        <!--<br>
        <button onclick="window.location.href = '/subcategoria/create';">Registrar nuevo subcategoria</button>-->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.scrollTo(0, document.body.scrollHeight);
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