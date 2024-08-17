<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Equipo | Editar</title>
</x-plantilla-head>

<x-plantilla-body>

    <h1 style="margin-bottom: 15px;">Editar Equipo</h1>

    <!--editar formulario por medio de la direccion de route:list, esto porque como no tengo un archivo, necesito mandar llamas a la ruta de la lista asesor.update-->
    <form action="{{ route('equipo.update', $equipo)}}" method="post"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->

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
        <input type="text" id="nombre" name="nombre" placeholder="Nombre del equipo" minlength="4" maxlength="20" required value = "{{old('nombre') ?? $equipo -> nombre}}" autofocus><br><br>

        <label for="asesor" style="margin-bottom: 5px;"><b> Asesor: </b></label><br>
        <select name="asesor_id" required style="min-width:200px;"> <!--style="width: 200px;"-->
            @foreach($asesores as $asesor)
                <option value="{{ $asesor -> id }}" @if(old('asesor_id') == $asesor->id || $equipo->asesor_id == $asesor->id) selected @endif>
                    {{ $asesor->nombre }}
                </option>
            @endforeach
        </select><br><br>

        <label for="competencia" style="margin-bottom: 5px;"><b> Competencia: </b></label><br>
        <select name="competencia_id" required style="min-width:200px;">
            @if($competencias -> count() == 0)
                <option disabled selected>No hay competencias disponibles</option>
            @endif
            @foreach($competencias as $competencia)
                <option value="{{ $competencia -> id }}" @if(old('competencia_id') == $competencia->id || $equipo->competencia_id == $competencia->id) selected @endif>
                    {{ $competencia->identificador }}
                </option>
            @endforeach
        </select><br><br>

        <label for="categoria" style="margin-bottom: 5px;"><b> Categoria: </b></label><br>
        <select name="categoria_id" required style="min-width:200px;">
            @if($categorias -> count() == 0)
                <option disabled selected>No hay categorias disponibles</option>
            @endif
            @foreach($categorias as $categoria)
                <option value="{{ $categoria -> id }}" @if(old('categoria_id') == $categoria->id || $equipo->categoria_id == $categoria->id) selected @endif>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select><br><br>


        <input type="submit" value="Actualizar" style="margin-top: 20px;">
        <a href="{{ route('equipo.index') }}" style="margin-left:10px;">Cancelar</a>
    </form>

    </x-plantilla-body>

</html>