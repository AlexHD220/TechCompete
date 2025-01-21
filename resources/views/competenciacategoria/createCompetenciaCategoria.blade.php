<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Agregar Categoría</title>

    <style>    
        .disabled-input {
            opacity: 0.5; /* Opacidad al 50% */
            pointer-events: none; /* Evita interacción cuando está deshabilitado */
        }

        
        .enabled-input {
            opacity: 1; /* Opacidad al 100% */
            pointer-events: auto; /* Permite interacción */
        }
    </style>
</x-plantilla-head>

<x-plantilla-body>

    @if (session('alerta'))
        <script>                
            document.addEventListener('DOMContentLoaded', function () {            
                    // Captura los datos de la sesión y llama a la función                        
                    sweetAlertNotification("{{ session('alerta.titulo') }}", "{{ session('alerta.texto') }}", "{{ session('alerta.icono') }}", "{{ session('alerta.tiempo') }}", "{{ session('alerta.botonConfirmacion') }}");
            });
        </script>
    @endif

    <h1 style="margin-bottom: 15px;">Nueva Categoría ({{ $competencia->publicada ? '' : 'Borrador ' }}{{$competencia->name}})</h1>

    <form action="{{ route('competenciacategoria.store', $competencia) }}" method="post">

        <!--Mostrar errores-->
        @if ($errors->any() || session('missing_fecha') || session('missing_fecha_fin'))
            <div class="msgAlerta">
                <ul>
                    @if(session('missing_fecha'))
                        <li>El campo "Inicio de registros" es obligatorio.</li>
                    @endif

                    @if(session('missing_fecha_fin'))
                        <li>El campo "Cierre de registros" es obligatorio.</li>
                    @endif

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <br>
        @endif    

        @csrf <!--permite entrar al formulario muy importante agregar-->

        <label for="categoria_id" style="margin-bottom: 5px;"><b> Categoría: </b></label><br>
        <select id="categoria_id" name="categoria_id" required style="min-width:100px; " autofocus>
            <option selected disabled value="">Selecciona una opción</option>            
            @foreach($categorias as $categoria)
                <option value="{{ $categoria -> id }}" @if(old('categoria_id') == $categoria->id) selected @endif title="Tipo {{$categoria->tipo}}">
                    {{ $categoria->name }}
                </option>
            @endforeach
        </select><br><br>            

        <label for = "costo"><b>Costo: $</b></label>
        <input type="number" name="costo" id="costo" required value = "{{ old('costo') ? old('costo') : 0 }}" min="0" step="1" style="width: 75px;"> pesos mexicanos <br><br>


        <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; margin-top: 10px;">
            <div>
                <label class="switch">
                <input type="checkbox" id="registro_personalizado" name="registro_personalizado" {{ old('registro_personalizado') ? 'checked' : '' }}>
                <span class="slider"></span>
                </label>
            </div>
            
            <label for = "registro_personalizado"><b>Fecha de registro personalizada: </b></label>
        </div><br>

        <div id="fecha_personalizada" class="disabled-input">
            <label for = "inicio_registros"><b>Inicio de registros: </b></label>        
            <input type="date" id="inicio_registros" name="inicio_registros" required value = "{{ old('inicio_registros') }}" min="{{ now()->toDateString() }}" max="{{ \Carbon\Carbon::parse($competencia->fecha)->subDay(1)->format('Y-m-d') }}" disabled><br><br>

            <label for = "fin_registros"><b>Cierre de registros: </b></label>
            <input type="date" id="fin_registros" name="fin_registros" required value = "{{ old('fin_registros') }}" min="{{ now()->addDays(1)->toDateString() }}" max="{{ $competencia->fecha }}" disabled><br><br>
        </div>      
                
        <div style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">            
            @if($categorias->count() > 1)
                <input type="submit" name="action" value="Registrar Categoría&#10;y Agregar Nueva" style="width: 158.48px; text-align: center;">          
            @endif

            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                <input type="submit" name="action" value="Registrar Categoría">
                @if($competencia->publicada) 
                    <a href="{{ route('competencia.show', $competencia) }}">Cancelar</a>
                @else
                    <a href="{{ route('competencia.showdraft', $competencia) }}">Cancelar</a>
                @endif
            </div>
        </div>
    </form>

    <script>    

        if(document.getElementById('inicio_registros').value){
            document.getElementById('fin_registros').removeAttribute('disabled');
        }
        
        document.getElementById('inicio_registros').addEventListener('input', function() {
            var fechaFin = document.getElementById('fin_registros');
            if (this.value.trim() !== '') {
                fechaFin.removeAttribute('disabled');
                fechaFin.value = ""; // Limpia el valor de fecha_fin si se deshabilita
            } else {
                fechaFin.setAttribute('disabled', 'true');
                fechaFin.value = ""; // Limpia el valor de fecha_fin si se deshabilita
            }
        });

        
        const fechaInput = document.getElementById('inicio_registros');
        const fechaFinInput = document.getElementById('fin_registros');

        // Actualizar el mínimo de fecha_fin al cambiar fecha
        fechaInput.addEventListener('change', function () {
            if (fechaInput.value) {
                const fechaSeleccionada = new Date(fechaInput.value);
                fechaSeleccionada.setDate(fechaSeleccionada.getDate() + 1); // Sumar 1 día
                const minimoFechaFin = fechaSeleccionada.toISOString().split('T')[0]; // Formatear a YYYY-MM-DD

                fechaFinInput.min = minimoFechaFin;
            } else {                
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
                
                fechaFinInput.min = tomorrowFormatted;          
            }
        });

    </script>

    <script>
        // Referencias al checkbox y al input de fecha
        const checkbox = document.getElementById('registro_personalizado');
        const inicioRegistrosCheckbox = document.getElementById('inicio_registros');
        const finRegistrosCheCkbox = document.getElementById('fin_registros');

        // Referencias al checkbox y al input de fecha        
        const fechaPerzonalizadaCheckbox = document.getElementById('fecha_personalizada');

        if(checkbox.checked){
            inicioRegistrosCheckbox.removeAttribute('disabled'); // Habilitar el input                

            fechaPerzonalizadaCheckbox.classList.remove('disabled-input'); // Quitar opacidad del 50%
            fechaPerzonalizadaCheckbox.classList.add('enabled-input'); // Aplicar opacidad del 100%
        }

        // Escuchar cambios en el checkbox
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                inicioRegistrosCheckbox.removeAttribute('disabled'); // Habilitar el input                

                fechaPerzonalizadaCheckbox.classList.remove('disabled-input'); // Quitar opacidad del 50%
                fechaPerzonalizadaCheckbox.classList.add('enabled-input'); // Aplicar opacidad del 100%
            } 
            else {
                fechaPerzonalizadaCheckbox.classList.remove('enabled-input'); // Quitar opacidad del 100%
                fechaPerzonalizadaCheckbox.classList.add('disabled-input'); // Aplicar opacidad del 50%

                inicioRegistrosCheckbox.setAttribute('disabled', 'true'); // Deshabilitar el input
                finRegistrosCheCkbox.setAttribute('disabled', 'true'); // Deshabilitar el input

                inicioRegistrosCheckbox.value = ""; // Limpia el valor de fecha_inicio
                finRegistrosCheCkbox.value = ""; // Limpia el valor de fecha_fin                
            }
        });
    </script>

    </x-plantilla-body>

</html>