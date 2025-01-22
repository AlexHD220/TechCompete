<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Registro Juez</title>
</x-plantilla-head>

<x-plantilla-body>

    <h1 style="margin-bottom: 20px;">Generar código para registro de cuenta (Juez)</h1>

    <form action="/registrojuez" method="post"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->

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

        <!--@error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror-->

        @csrf <!--permite entrar al formulario muy importante agregar-->

        <!--<label for="usuario"><b> Usuario: </b></label>
        <input type="text" id="usuario" name="usuario" placeholder="Usuario" required value = "{{ old('usuario') }}"><br><br>--> <!--value = "{{old('name')}}"-->

        <label for = "email"><b>Correo Electrónico: </b></label>
        <input type="email" name="email" minlength="5" maxlength="50" required value = "{{ old('email') }}" style="width: 337px; margin: 5px" autofocus><br><br>

        <!-- Selector de tipo de expiración -->
        <label for="expiration_type"><b>Tipo de Fecha Límite: </b></label>
        <select id="expiration_type" name="expiration_type" onchange="toggleExpirationInputs()" style="width: 200px; margin: 5px;" required>        
            <option selected disabled value=""> - </option>
            <option value="days" @selected(old('expiration_type') == 'days')>Por número de días</option>
            <option value="specific_date" @selected(old('expiration_type') == 'specific_date')>Por fecha específica</option>                         
        </select><br><br>

        <!-- Entrada para días -->
        <div id="days_input" style="display: none;">
            <label for="days"><b>Número de Días: </b></label>            
            <input type="number" id="days" name="days" value = "{{ old('days') }}" min="3" max="100" step="1" style="width: 55px;"> días <br><br>
        </div>

        <!-- Entrada para fecha específica -->
        <div id="specific_date_input" style="display: none;">
            <label for="specific_date"><b>Fecha específica: </b></label>        
            <input type="date" id="specific_date" name="specific_date" value = "{{ old('specific_date') }}" min="{{ now()->addDays(3)->toDateString() }}" max="{{ now()->addDays(100)->toDateString() }}"><br><br>
        </div>

        <input type="submit" value="Generar código" style="margin-top: 10px;"> 
        <a href="{{ route('registrojuez.index') }}" style="margin-left:20px;">Cancelar</a>
    </form>

    <script>

        if(document.getElementById('expiration_type').value == 'days'){
            document.getElementById('days_input').style.display = 'block';
            daysInput.setAttribute('required', 'required');
        }
        else if(document.getElementById('expiration_type').value == 'specific_date'){
            document.getElementById('specific_date_input').style.display = 'block';
            specificDateInput.setAttribute('required', 'required');
        }

        function toggleExpirationInputs() {
            const type = document.getElementById('expiration_type').value;

            // Obtener los campos de entrada
            const daysInput = document.getElementById('days');
            const specificDateInput = document.getElementById('specific_date');

            // Reiniciar estilos y atributos
            document.getElementById('days_input').style.display = 'none';
            document.getElementById('specific_date_input').style.display = 'none';
            daysInput.removeAttribute('required');
            specificDateInput.removeAttribute('required');

            // Limpiar campos ocultos
            daysInput.value = '';  // Limpiar el valor 
            specificDateInput.value = '';  // Limpiar el valor

            // Configurar según la selección
            if (type === 'days') {
                document.getElementById('days_input').style.display = 'block';
                daysInput.setAttribute('required', 'required');
            } else if (type === 'specific_date') {
                document.getElementById('specific_date_input').style.display = 'block';
                specificDateInput.setAttribute('required', 'required');
            }
        }

        // Inicializar la vista
        //toggleExpirationInputs();
    </script>

    </x-plantilla-body>

</html>