<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Crear Categoría</title>

    <style>
        .width-descripcion {
            width: 50%;
        }

        /* Pantallas pequeñas: celulares en orientación vertical */
        /*@media (max-width: 768px) {*/
        @media (max-width: 700px) {
            .width-descripcion {
                width: 70%;
            }
        }

        @media (max-width: 450px) {
            .width-descripcion {
                width: 90%;
            }
        }
    </style>
</x-plantilla-head>

<x-plantilla-body>

    <h1 style="margin-bottom: 15px;">Nueva Categoría</h1>

    <form action="/categoria" method="post"> <!--la diagonal me envia al principio de la url "techcompete.test/"-->

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

        <label for="name"><b> Nombre: </b></label>
        <input type="text" id="name" name="name" style="width: 250px" placeholder="Identificador" required value = "{{ old('name') }}" autofocus><br><br> <!--value = "{{old('name')}}"-->

        <label for="tipo" style="margin-bottom: 5px;"><b>Tipo de inscripciones: </b></label><br>
        <select name="tipo" id="tipo" required style="width: 110px; height: 30px;">
            <option selected disabled value=""> - </option>
            <option value="Cualquiera" @selected(old('tipo') == 'Cualquiera')>Cualquiera</option>
            <option value="Equipos" @selected(old('tipo') == 'Equipos')>Equipos</option>
            <option value="Proyectos" @selected(old('tipo') == 'Proyectos')>Proyectos</option>
            <!--<option value="Individual" @selected(old('tipo') == 'Individual')>Individual</option>-->
        </select><br><br>

        <label for="descripcion" style="margin-bottom: 5px;"><b> Descripción: </b></label><br>
        <textarea class="width-descripcion" id="descripcion" name="descripcion" rows="4" style="resize: none;" minlength="1" maxlength="600" required>{{ old('descripcion') }}</textarea><br><br>

        <!--<label for="escuela"><b> Escuela: </b></label>
        <input type="text" name="escuela" list="listaEscuelas" value = "{{ old('escuela') }}"><br><br>-->

        <input type="submit" value="Registrar Categoría" style="margin-top: 10px;"> 
        <a href="{{ route('categoria.index') }}" style="margin-left:10px;">Cancelar</a>
    </form>

    </x-plantilla-body>

</html>