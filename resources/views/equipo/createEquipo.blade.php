<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Equipo | Formulario</title>
</x-plantilla-head>

<x-plantilla-body>

    <h1 style="margin-bottom: 15px;">Registrar Nuevo Equipo</h1>

    <form action="/equipo" method="post"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->

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

        <label for="nombre"><b> Nombre: </b></label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre del equipo" minlength="4" maxlength="20" required value = "{{ old('nombre') }}" autofocus><br><br>

        <label for="asesor" style="margin-bottom: 5px;"><b> Asesor: </b></label><br>
        <select name="asesor_id" required style="min-width:200px;"> <!--style="width: 200px;"-->
            <option disabled selected>Selecciona una opción</option>
            @foreach($asesores as $asesor)
                <option value="{{ $asesor -> id }}" @if(old('asesor_id') == $asesor->id) selected @endif>
                    {{ $asesor->nombre }}
                </option>
            @endforeach
        </select><br><br>

        <label for="competencia" style="margin-bottom: 5px;"><b> Competencia: </b></label><br>
        <select name="competencia_id" required style="min-width:200px;">
            <option disabled selected>Selecciona una opción</option>
            @foreach($competencias as $competencia)
                <option value="{{ $competencia -> id }}" @if(old('competencia_id') == $competencia->id) selected @endif>
                    {{ $competencia->identificador }}
                </option>
            @endforeach
        </select><br><br>

        <label for="categoria" style="margin-bottom: 5px;"><b> Categoría: </b></label><br>
        <select name="categoria_id" required style="min-width:200px;">
            <option disabled selected>Selecciona una opción</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria -> id }}" @if(old('categoria_id') == $categoria->id) selected @endif>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select><br><br>

        <input type="submit" value="Registrar" style="margin-top: 10px;"> 
        <a href="{{ route('equipo.index') }}" style="margin-left:10px;">Cancelar</a>
    </form>

    </x-plantilla-body>

</html>