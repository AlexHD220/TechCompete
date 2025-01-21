<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Editar Subcategoría</title>
</x-plantilla-head>

<x-plantilla-body>
    <div>
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Subcategorías (Editar)</h1>            
        </div>

        
        <div style="margin-top: 15px;">        
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
                
                <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">                        

                    <div style="position: relative; display: inline-block;">
                        <input type="text" id="nivel" name="nivel" style="width: 250px; padding-right: 30px; margin-right: 5px;" placeholder="Editar Subcategoría" required value = "{{ old('nivel') ?? $subcategoria -> nivel }}" autofocus> <!--value = "{{old('nivel')}}"-->
                        
                        <i class="fas fa-times" id="limpiar" title="Limpiar Input" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; color: gray;"></i>
                    </div><br><br>

                    <input type="submit" id="submitButton" value="Actualizar Subcategoría"> 
                    <a href="{{ route('subcategoria.create') }}">Cancelar</a>
                    <!--<a href="{{ route('subcategoria.create') }}" style="margin-left:10px;">Cancelar</a>-->
                </div>
            </form>                       
        </div>        

            
        <div style="margin-top: 20px;">

            @foreach ($subcategorias as $subcategoriaone) <!--Listar todos los datos de la tabla user-->
                
                <li>                                                
                    <b style="font-size: 20px;">{{ $subcategoriaone -> nivel }}</b>                                                        
                </li><br>
                
            @endforeach                
        </div><br>                 

        <!--<br>
        <button onclick="window.location.href = '/subcategoria/create';">Registrar nuevo subcategoria</button>-->
    </div>

    <!--<script>
        document.addEventListener('DOMContentLoaded', function() {
            window.scrollTo(0, document.body.scrollHeight);
        });
    </script>-->

    <script>
        document.getElementById('nivel').addEventListener('input', function() {
            var submitButton = document.getElementById('submitButton');
            var limpiarInput = document.getElementById('limpiar');

            if (this.value.trim() !== '') {
                submitButton.removeAttribute('disabled');
                limpiarInput.style.visibility = "visible";
            } else {
                submitButton.setAttribute('disabled', 'true');
                limpiarInput.style.visibility = "hidden";
            }
        });

        document.getElementById("limpiar").addEventListener("click", function () {
            var limpiarInput = document.getElementById('limpiar');
            var nivelInput = document.getElementById('nivel');

            limpiarInput.style.visibility = "hidden";
            nivelInput.value = "";
            nivelInput.focus();
        });
    </script>
</x-plantilla-body>

</html>