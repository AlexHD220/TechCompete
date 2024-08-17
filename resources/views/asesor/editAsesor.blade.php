<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Asesor | Editar</title>
</x-plantilla-head>

<x-plantilla-body>

    <h1 style="margin-bottom: 15px;">Editar Asesor</h1>

    <!--editar formulario por medio de la direccion de route:list, esto porque como no tengo un archivo, necesito mandar llamas a la ruta de la lista asesor.update-->
    <form action="{{ route('asesor.update', $asesor)}}" method="post"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->

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

        <!--<label for="usuario"><b> Usuario: </b></label>
        <input type="text" id="usuario" name="usuario" placeholder="Usuario" required value = "{{old('usuario') ?? $asesor -> usuario}}"><br><br>-->

        <label for="nombre"><b> Nombre: </b></label>
        <input type="text" name="nombre" placeholder="Nombre Completo" minlength="10" maxlength="50" required value = "{{old('nombre') ?? $asesor -> nombre}}" style="width: 300px;" autofocus><br><br>

        <label for = "correo"><b>Correo: </b></label>
        <input type="email" name="correo" minlength="5" maxlength="50" required value = "{{old('correo') ?? $asesor -> correo}}" style="width: 300px;"><br><br>

        <label for = "telefono"><b>Telefono: </b></label>
        <input type="tel" name="telefono" placeholder="10 dígitos" maxlength="10" value = "{{old('telefono') ?? $asesor -> telefono}}"> (opcional)<br><br>

        <!--<label for="escuela"><b> Escuela: </b></label>
        <input type="text" name="escuela" list="listaEscuelas" value = "{{old('pass') ?? $asesor -> escuela}}"><br><br>-->


        <input type="submit" value="Actualizar" style="margin-top: 10px;">
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