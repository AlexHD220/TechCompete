<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Asesor | Formulario</title>
</x-plantilla-head>

<x-plantilla-body>

    <h1 style="margin-bottom: 15px;">Registrar Asesor</h1>

    <form action="/asesor" method="post"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->

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

        <!--<label for="usuario"><b> Usuario: </b></label>
        <input type="text" id="usuario" name="usuario" placeholder="Usuario" required value = "{{ old('usuario') }}"><br><br>--> <!--value = "{{old('name')}}"-->

        <label for="nombre"><b> Nombre: </b></label>
        <input type="text" id = "nombre" name="nombre" placeholder="Nombre Completo" minlength="10" maxlength="50" required value = "{{ old('nombre') }}" style="width: 300px;" autofocus><br><br>

        <label for = "correo"><b>Correo: </b></label>
        <input type="email" name="correo" minlength="5" maxlength="50" required value = "{{ old('correo') }}" style="width: 300px;"><br><br>

        <label for = "telefono"><b>Telefono: </b></label>
        <input type="tel" name="telefono" placeholder="10 dígitos" maxlength="10" value = "{{ old('telefono') }}"> (opcional)<br><br>

        <!--<label for="escuela"><b> Escuela: </b></label>
        <input type="text" name="escuela" list="listaEscuelas" value = "{{ old('escuela') }}"><br><br>-->

        <!--<label for="pass"><b> Contraseña: </b></label>
        <input type="password" id="pass" name="pass" required value = "{{ old('pass') }}">
        <button type="button" id="showPassword" onclick="cambiarTexto()" style="margin-left: 5px">Mostrar</button><br><br>-->

        <!--Seleccion multiple []-->
        <!--{{--<br>
        <select name="organizacion_id[]" multiple>
            @foreach($orgs as $org)
                <option value="{{ $org -> id }}" @selected(array_search($org->id, old('organizacion_id') ?? []) !== false)>
                    {{ $org->nombre }}
                </option>
            @endforeach
        </select>
        <br>--}}-->

        <input type="submit" value="Registrar" style="margin-top: 10px;"> 
        <a href="{{ route('asesor.index') }}" style="margin-left:10px;">Cancelar</a>
    </form>

    <!--<br>
    <button onclick="window.location.href = '/asesor';">Cancelar</button>-->

    <!--<datalist id="listaEscuelas">
    <option value="Centro Universitario de Ciencias Exactas e Ingenierias">
    <option value="Centro Universitario de Ciencias Economico Administrativas">
    <option value="Colegio Republica Mexicana">
    <option value="Colegio Rafael Guizar">
    <option value="Colegio Versalles">
    <option value="Universidad Autonoma del Valle de Mexico">
    </datalist>-->

    </x-plantilla-body>

</html>