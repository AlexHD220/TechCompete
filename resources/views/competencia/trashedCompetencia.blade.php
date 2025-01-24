<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Competencias Eliminadas</title>

    <style>
        .competencias-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Dos columnas por defecto */
            /*gap: 25px;*/ /* Espaciado entre columnas y filas */
            row-gap: 30px;   /* Espaciado entre filas */
            column-gap: 25px; /* Espaciado entre columnas */
            
            
            justify-items: center; /* Centra horizontalmente */
            align-items: center; /* Centra verticalmente */
        }

        /* Pantallas intermedias: tabletas o celulares horizontales */
        /*@media (max-width: 1024px) {*/
        @media (max-width: 991.98px) {
            .competencias-container {
                grid-template-columns: repeat(2, 1fr); /* Cambiar a dos columnas por fila */
            }
        }

        /* Pantallas pequeñas: celulares en orientación vertical */
        /*@media (max-width: 768px) {*/
        @media (max-width: 650px) {
            .competencias-container {
                grid-template-columns: 1fr; /* Cambiar a una columna por fila */
            }
        }   

        .sombra {
            /*box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.5);*/
            box-shadow: 0px 0px 5px 3px rgba(255,0,0,0.2);
        }
    </style>
</x-plantilla-head>

<x-plantilla-body>

<!--@php
$timestampNow = now()->toDateString();
@endphp
<p>Timestamp actual: {{ $timestampNow }}</p>-->

<!-- Declarar variables dentro de una vista -->
<!--@php
    $rutaAnterior = url()->previous();    
    $rutaActual = request()->path();
@endphp-->

    @if (session('alerta'))
        <script>                
            document.addEventListener('DOMContentLoaded', function () {            
                    // Captura los datos de la sesión y llama a la función                        
                    sweetAlertNotification("{{ session('alerta.titulo') }}", "{{ session('alerta.texto') }}", "{{ session('alerta.icono') }}", "{{ session('alerta.tiempo') }}", "{{ session('alerta.botonConfirmacion') }}");
            });
        </script>
    @endif

    @if (session('aviso'))
        <script>                
            document.addEventListener('DOMContentLoaded', function () {            
                    // Captura los datos de la sesión y llama a la función                        
                    sweetAlertPersistent("{{ session('aviso.titulo') }}", "{{ session('aviso.texto') }}", "{{ session('aviso.icono') }}");
            });
        </script>
    @endif

    @if (session('cambiarNombre'))
        <script>                
            document.addEventListener('DOMContentLoaded', function () {                               
                trashedCambiarNombre("{{ session('cambiarNombre.titulo') }}", "{{ session('cambiarNombre.texto') }}", "{{ session('cambiarNombre.icono') }}", "{{ session('cambiarNombre.confirmButtonText') }}", "{{ session('cambiarNombre.inputValue') }}");
            });
        </script>
    @endif

<div>

    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
        <h1 style="display: inline;">Listado de Competencias Eliminadas</h1>
    </div>

    @if ($competencias->count() == 0)
        <p style="margin-left: 20px;"><i>Actualmente no hay ninguna competencia eliminada.</i></p>
    @endif

    @if ($competencias->count() > 0)
        <h3>Próximas competencias eliminadas.</h3>

        <!-- Contenedor con grid -->
        <div class="competencias-container" style="margin-top: 15px; margin-bottom: 35px;">
            @foreach ($competencias as $competencia)                          

                <!--<div class="competencia-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;">-->
                <div class="competencia-card">
                    <!-- Imagen -->
                    <div class="text-center"> 
                        <a href="{{ route('competencia.showtrashed', $competencia->id) }}" style="text-decoration: none; color: inherit;">
                            <img class="sombra" src="{{ \Storage::url($competencia->ubicacion_imagen) }}" alt="Logo competencia" style="width: 100%; max-height:px; object-fit: cover; border-radius: 10px;">
                        </a>                            
                    </div>

                    <div class="text-center" style="margin-top: 10px;">
                        <a href="{{ route('competencia.showtrashed', $competencia->id) }}" style="text-decoration: none; color: inherit;">
                            <b style="font-size: 25px;">{{ $competencia->name }}</b>
                        </a>                            
                    </div>

                    <div class="text-center" style="margin-top: 0px;">
                        <a href="{{ $competencia->mapa_link }}" style="text-decoration: none;" target="_blank" rel="noopener noreferrer" title="Ubicación">
                            <h style="font-size: 15px;">{{ $competencia->ubicacion }}</h>
                        </a>
                    </div>

                    <!--<div class="text-center" style="margin-top: 0px;">                            
                        <h style="font-size: 15px;">{{ $competencia->sede }}</h>                            
                    </div>-->

                    <div class="text-center" style="margin-top: 5px; font-size: 16px;">
                        <b>Fecha:</b> {{ date('d/m/Y', strtotime($competencia->fecha)) }}
                    </div>

                    @auth
                        @can('only-superadmin')
                            <div class="text-center" style="margin-top: 10px;">                                    

                                <form action="{{route('competencia.restore', $competencia->id)}}" method = "POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <button type="submit" onclick="return confirm('¿Está seguro que desea restaurar esta competencia?')" onmouseover="this.style.backgroundColor='#63b38e';" onmouseout="this.style.backgroundColor='#198754';"  
                                    style="margin-left: 5px; margin-right: 5px; background-color: #198754; color: white; border: none; padding: 5px 10px; border-radius: 5px; display: inline-flex; justify-content: center; align-items: center;"
                                    title="Restaurar Competencia">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-refresh" style="font-size: 13px; margin-right: 8px;"></i> <!-- Ícono de FontAwesome -->                                                                                                                    
                                            <h style="font-size: 15px;">Restaurar competencia</h>
                                        </div>
                                    </button>
                                </form>

                                <form id="formUpdateName" action="{{ route('competencia.updateName', $competencia->id) }}" method="post">    
                                    @csrf <!--permite entrar al formulario muy importante agregar-->
                                    @method ('PATCH') <!--permite truquear nuestro formulario para editar la informacion-->

                                    <input type="hidden" id="updateName" name="name"> <!-- Aquí se asignará el nuevo nombre -->
                                </form>
                                
                                @if(0) 
                                    <form action="{{route('competencia.destroy', $competencia)}}" method = "POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')

                                        <!--Enviar datos ocultos de una vista al controlador-->
                                        <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">
                                        
                                        <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta competencia de forma permanente?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                        style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                        title="Eliminar Borrador">
                                            <i class="fas fa-trash" style="font-size: 20px;"></i> <!-- Ícono de FontAwesome -->
                                        </button>
                                    </form>  
                                @endif 
                            </div>
                        @endcan
                    @endauth
                </div>
                
            @endforeach
        </div>
    @endif

    @if ($expiradas->count() > 0)
        <div class="d-flex justify-content-between align-items-center" style="margin-top: 35px; margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-items: center;">
            <h3>Competencias expiradas eliminadas.</h3>
    
            @if(0)
                <form action="{{ route('competencia.destroyexpiradas') }}" method="POST"  onsubmit="return confirm('¿Está seguro que desea eliminar todas las competencias expirados de forma permanente?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-trash" style="font-size: 12px; margin-right: 8px;"></i> <!-- Ícono de FontAwesome -->
                            Eliminar borradores expirados
                        </div>
                    </button>
                </form>  
            @endif                      
        </div> 
        
        <!-- Codigo -->
        <div style="margin-top: 15px;">

            @foreach ($expiradas as $expirada) <!--Listar todos los datos de la tabla user-->
                
                <li>                  
                    
                    <!--<a href="{{ route('competencia.showdraft', $expirada) }}" style="text-decoration: none; color: inherit;">                        
                        <b style="font-size: 20px;">{{ $expirada -> name }}</b>
                    </a>-->
                    <b style="font-size: 20px;">{{ $expirada -> name }}</b>

                    (<a href="{{ $expirada->mapa_link }}" style="text-decoration: none;" target="_blank" rel="noopener noreferrer" title="Ubicación">
                        <h style="font-size: 15px;">{{ $expirada->ubicacion }}</h>
                    </a>)

                    <b style="font-size: 20px;"> | </b>

                    @if($expirada->fecha == $expirada->fecha_fin)
                        <b style="font-size: 17px; cursor: pointer;" 
                            onmouseover="this.style.color='#9294a1';" 
                            onmouseout="this.style.color='#6c7293';" 
                            title="Fecha de Inicio y Fin">
                            {{ date('d/m/Y', strtotime($expirada->fecha)) }}
                        </b>
                    @else
                        <b id="fecha" 
                            style="font-size: 17px; cursor: pointer;" 
                            onmouseover="this.style.color='#9294a1';" 
                            onmouseout="this.style.color='#6c7293';" 
                            data-inicio="{{ date('d/m/Y', strtotime($expirada->fecha)) }}" 
                            data-fin="{{ date('d/m/Y', strtotime($expirada->fecha_fin)) }}" 
                            title="Fecha de Inicio"
                            onclick="toggleCambiarFechaExpirada(this)">
                            {{ date('d/m/Y', strtotime($expirada->fecha)) }}
                        </b>
                    @endif
                    
                    @if(0)
                        <h style="margin-right: 5px;"></h>

                        <form action="{{route('competencia.destroy', $expirada)}}" method = "POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')

                            <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">
                            
                            <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar esta competencia de forma permanente?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                            style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                            title="Eliminar Borrador">
                                <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                            </button>
                        </form>   
                    @endif                                             

                </li><br>
                
            @endforeach
        </div>

    @endif       

</div>

<script>
    
    function trashedCambiarNombre(titulo, texto, icono, confirmButtonText, inputValue) {

        const swalWithBootstrapButtons = Swal.mixin({
        /*customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-secondary"
        },*/
        buttonsStyling: true
        });
    
        swalWithBootstrapButtons.fire({        
        title: titulo,
        text: texto,
        icon: icono,
        showCancelButton: true,
        //CancelButtonColor: "#7066e0",
        //confirmButtonColor: "#3085d6",
        confirmButtonText: confirmButtonText,
        cancelButtonText: "OK",
        reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar la ventana emergente para cambiar el nombre
            Swal.fire({
            title: "Actualizar nombre y restaurar.",
            input: "text",
            inputValue: inputValue, // Valor inicial del nombre actual de la competencia.
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Guardar cambios",
            preConfirm: (nuevoNombre) => {
                if (!nuevoNombre || nuevoNombre.trim() == "") {                
                    Swal.showValidationMessage("El nombre no puede estar vacío.");
                    return false; // Detiene la ejecución si el campo está vacío.
                }
                else{
                    return nuevoNombre.trim(); // Retorna el valor limpio.
                }                
            }
            }).then((result) => {
            if (result.isConfirmed) {
                // Asignar el valor al input hidden y enviar el formulario.
                document.getElementById("updateName").value = result.value; // Asigna el valor ingresado.
                document.getElementById("formUpdateName").submit(); // Envía el formulario.
            }
            });
        }
        });

    }
    
</script>


</x-plantilla-body>

</html>