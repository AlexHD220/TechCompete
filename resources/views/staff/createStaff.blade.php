<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Staff | Cuenta</title>
</x-plantilla-head>

<x-plantilla-body>

    <h1 style="margin-bottom: 20px;">Crear cuenta de staff</h1>

    <form action="/staff" method="post"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->

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

        <label for="rol"><b>Tipo de cuenta: </b></label>
        <select name="rol" style="width: 200px; margin: 5px;" autofocus required>
            <option selected disabled value=""> - </option>
            <option value=3 @selected(old('rol') == 3)>SuperStaff</option>
            <option value=4 @selected(old('rol') == 4)>Staff</option>
        </select><br><br>

        <label for="name"><b> Nombre(s): </b></label>
        <input type="text" id = "name" name="name" minlength="3" maxlength="50" required value = "{{ old('name') }}" style="width: 400px; margin: 5px"><br><br>

        <label for="lastname"><b> Apellido(s): </b></label>
        <input type="text" id = "lastname" name="lastname" minlength="5" maxlength="50" required value = "{{ old('lastname') }}" style="width: 400px; margin: 5px"><br><br>

        <label for = "email"><b>Correo Electrónico: </b></label>
        <input type="email" name="email" minlength="5" maxlength="50" required value = "{{ old('email') }}" style="width: 337px; margin: 5px"><br><br>

        <input type="submit" value="Crear cuenta" style="margin-top: 10px;"> 
        <a href="{{ route('staff.index') }}" style="margin-left:20px;">Cancelar</a>
    </form>

    </x-plantilla-body>

</html>