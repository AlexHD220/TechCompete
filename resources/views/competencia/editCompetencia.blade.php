<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Categoría | Editar</title>
</x-plantilla-head>

<x-plantilla-body>

    <h1 style="margin-bottom: 15px;">Editar Categoría</h1>

    <!--editar formulario por medio de la direccion de route:list, esto porque como no tengo un archivo, necesito mandar llamar a la ruta de la lista asesor.update-->
    <form action="{{ route('competencia.update', $competencia)}}" method="post"  enctype="multipart/form-data"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->

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


        <label for="identificador"><b> Identificador: </b></label>
        <input type="text" id="identificador" name="identificador" placeholder="Identificador" minlength="5" maxlength="50" required value = "{{old('identificador') ?? $competencia -> identificador}}" autofocus><br><br> <!--value = "{{old('name')}}"-->

        <label for = "fecha"><b>Fecha: </b></label>
        <input type="date" name="fecha" required value = "{{old('fecha') ?? $competencia -> fecha}}" max="{{ now()->addYears(2)->toDateString() }}"><br><br>

        <label for = "duracion"><b>Duración: </b></label>
        <input type="number" name="duracion" id="duracion" required value = "{{old('duracion') ?? $competencia -> duracion}}" min="1" max="100" step="1" style="width: 50px;"> días <br><br>

        <label for="tipo"><b>Tipo: </b></label>
        <select name="tipo" required style="width: 90px;">
            <option value="Equipo" @selected( (old('tipo') ?? $competencia->tipo) == 'Equipo')>Equipo</option>
            <option value="Proyecto" @selected( (old('tipo') ?? $competencia->tipo) == 'Proyecto')>Proyecto</option>
        </select><br><br>

        <label for = "categoria_id" style="margin-bottom: 5px;"><b>Categorías: </b></label><br>
        <select name="categoria_id[]" id="categoria_id" multiple style="width: 200px;" required> <!--Seleccion multiple []-->
            @foreach($categorias as $categoria)                
                <option value="{{ $categoria->id }}" @if(in_array($categoria->id, old('categoria_id', $competencia->categorias->pluck('id')->toArray()) ?? [])) selected @endif>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select><br><br>

        <label for="imagen" style="margin-bottom: 5px;"><b> Cargar nueva imagen: </b></label><br>
        <input type="file" id="imagen" name="imagen" placeholder="imagen" accept=".png, .jpg, .jpeg"><br><br> <!--value = "{{old('name')}}"-->


        <input type="submit" value="Actualizar" style="margin-top: 10px;">
        <a href="{{ route('competencia.index') }}" style="margin-left:10px;">Cancelar</a>
    </form>

    </x-plantilla-body>

</html>