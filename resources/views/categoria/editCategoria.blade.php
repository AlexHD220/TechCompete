<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Categoría | Editar</title>
</x-plantilla-head>

<x-plantilla-body>

    <h1 style="margin-bottom: 15px;">Editar Categoría</h1>

    <!--editar formulario por medio de la direccion de route:list, esto porque como no tengo un archivo, necesito mandar llamar a la ruta de la lista asesor.update-->
    <form action="{{ route('categoria.update', $categoria)}}" method="post"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->

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

        <label for="nombre"><b> Nombre: </b></label>
        <input type="text" id="nombre" name="nombre" placeholder="Categoría" minlength="5" maxlength="50" required value = "{{old('nombre') ?? $categoria -> nombre}}" autofocus><br><br> <!--value = "{{old('name')}}"-->

        <label for="descripcion" style="margin-bottom: 5px;"><b> Descripción: </b></label><br>
        <textarea id="descripcion" name="descripcion" rows="4" style="resize: none; width: 400px;" minlength="10" maxlength="500" required>{{old('descripcion') ?? $categoria -> descripcion}}</textarea><br><br>

        <!--<label for="escuela"><b> Escuela: </b></label>
        <input type="text" name="escuela" list="listaEscuelas" value = "{{ old('escuela') }}"><br><br>-->

        <input type="submit" value="Guardar cambios" style="margin-top: 10px;"> 
        <a href="{{ route('categoria.index') }}" style="margin-left:10px;">Cancelar</a>
    </form>

    </x-plantilla-body>

</html>