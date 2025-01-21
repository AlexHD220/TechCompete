<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Agregar Subcategoría</title>

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

    <h1 style="margin-bottom: 15px;">Nueva Subcategoría ({{ $competencia->publicada ? '' : 'Borrador ' }}{{$competencia->name}})</h1>

    <form action="{{ route('competenciasubcategoria.store', [$competencia, $competenciaCategoria]) }}" method="post">

        <!--Mostrar errores-->
        @if ($errors->any() || session('missing_costo'))
            <div class="msgAlerta">
                <ul>
                    @if(session('missing_costo'))
                        <li>El campo "Costo" es obligatorio.</li>
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

        <label for="nivel" style="margin-bottom: 5px;"><b> Subcategoría: </b></label><br>
        <select id="nivel" name="nivel" required style="min-width:100px; " autofocus>
            <option selected disabled value="">Selecciona una opción</option>            
            @foreach($subcategorias as $subcategoria)
                <option value="{{ $subcategoria -> nivel }}" @if(old('nivel') == $subcategoria->nivel) selected @endif">
                    Nivel {{ $subcategoria->nivel }}
                </option>
            @endforeach
        </select><br><br>                    


        <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; margin-top: 10px;">
            <div>
                <label class="switch">
                <input type="checkbox" id="costo_personalizado" name="costo_personalizado" {{ old('costo_personalizado') ? 'checked' : '' }}>
                <span class="slider"></span>
                </label>
            </div>
            
            <label for = "costo_personalizado"><b>Costo de inscripción personalizado: </b></label>
        </div><br>

        <div id="personalizar_costo" class="disabled-input">

            <label for = "costo"><b>Costo: $</b></label>
            <input type="number" name="costo" id="costo" required value = "{{ old('costo') }}" min="0" step="1" style="width: 75px;" disabled> pesos mexicanos <br><br>
        </div>   
        
        <label for = "limite_inscripciones"><b>Limite de inscripciones: </b></label>
        <input type="number" name="limite_inscripciones" id="limite_inscripciones" value = "{{ old('limite_inscripciones') }}" min="2" step="1" style="width: 75px;"> lugares <br><br>


        @if($categoria->tipo == 'Equipos')
            <label for = "min_participantes"><b>Mínimo de participantes por equipo: </b></label> 
        @elseif($categoria->tipo == 'Proyectos')
            <label for = "min_participantes"><b>Mínimo de participantes por proyecto: </b></label>
        @endif
        <input type="number" name="min_participantes" id="min_participantes" required value = "{{ old('min_participantes') ? old('min_participantes') : 1 }}" min="1" step="1" style="width: 50px;"><br><br>
        
        @if($categoria->tipo == 'Equipos')
            <label for = "max_participantes"><b>Máximo de participantes por equipo: </b></label> 
        @elseif($categoria->tipo == 'Proyectos')
            <label for = "max_participantes"><b>Máximo de participantes por proyecto: </b></label>
        @endif
        <input type="number" name="max_participantes" id="max_participantes" required value = "{{ old('max_participantes') ? old('max_participantes') : 1 }}" min="1" step="1" style="width: 50px;"><br><br>
                
        <div style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">            
            @if($subcategorias->count() > 1)
                <input type="submit" name="action" value="Registrar Subcategoría&#10;y Agregar Nueva" style="width: 184.44px; text-align: center;">          
            @endif

            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                <input type="submit" name="action" value="Registrar Subcategoría">
                @if($competencia->publicada) 
                    <a href="{{ route('competenciacategoria.show', [$competencia, $competenciaCategoria]) }}">Cancelar</a>
                @else
                    <a href="{{ route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]) }}">Cancelar</a>
                @endif
            </div>
        </div>
    </form>

    <script>
        // Referencias al checkbox y al input de fecha
        const checkbox = document.getElementById('costo_personalizado');
        const costoCheckbox = document.getElementById('costo');        

        // Referencias al checkbox y al input de fecha        
        const personalizarCostoCheckbox = document.getElementById('personalizar_costo');

        if(checkbox.checked){
            costoCheckbox.removeAttribute('disabled'); // Habilitar el input                

            personalizarCostoCheckbox.classList.remove('disabled-input'); // Quitar opacidad del 50%
            personalizarCostoCheckbox.classList.add('enabled-input'); // Aplicar opacidad del 100%
        }

        // Escuchar cambios en el checkbox
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                costoCheckbox.removeAttribute('disabled'); // Habilitar el input   
                costoCheckbox.value = 0; // Limpia el valor de fecha_inicio               

                personalizarCostoCheckbox.classList.remove('disabled-input'); // Quitar opacidad del 50%
                personalizarCostoCheckbox.classList.add('enabled-input'); // Aplicar opacidad del 100%
            } 
            else {
                personalizarCostoCheckbox.classList.remove('enabled-input'); // Quitar opacidad del 100%
                personalizarCostoCheckbox.classList.add('disabled-input'); // Aplicar opacidad del 50%

                costoCheckbox.setAttribute('disabled', 'true'); // Deshabilitar el input                
                costoCheckbox.value = ""; // Limpia el valor de fecha_inicio                
            }
        });
    </script>

    </x-plantilla-body>

</html>