<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Mostrar Categoría</title>

    <style>
        .subcategorias-container {
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
            .subcategorias-container {
                grid-template-columns: repeat(2, 1fr); /* Cambiar a dos columnas por fila */
            }
        }

        /* Pantallas pequeñas: celulares en orientación vertical */
        /*@media (max-width: 768px) {*/
        @media (max-width: 650px) {
            .subcategorias-container {
                grid-template-columns: 1fr; /* Cambiar a una columna por fila */
            }
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

    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 0px; justify-content: center;">
                
        <div>                            
            <div style="display: flex; gap: 6px;">
                <h1 style="margin-bottom: 10px;">{{ $competencia->publicada ? '' : 'Borrador ' }}{{$competencia->name}}</h1>  
            </div>

            <div style="display: flex; gap: 6px;">
                <h2> {{ $categoria -> name }}</h2> 
            </div>
        </div>

        @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->   
            @if(!$competencia->enProgreso && !$competencia->pasada)  
                @if(Gate::allows('only-superadmin'))  
                    @if($subcategoriascount == 0 && $todasregistradas == true)
                        <div style="text-align: center;">
                            <a href="/subcategoria" style="font-size: 14px;">
                                <i>Ya se han registrado todos las niveles<br>
                                        de participación disponibles</i></a>
                        </div>   
                    @elseif($subcategoriascount > 0 && $competencia->publicada)             
                        <button class="btn btn-primary" link="{{ route('competenciasubcategoria.create', [$competencia, $competenciaCategoria]) }}" 
                        onclick="window.location.href = this.getAttribute('link');">
                            Agregar nivel de participación
                        </button> <!-- Link pendiente -->  
                    @elseif($subcategoriascount > 0 && !$competencia->publicada)             
                        <button class="btn btn-primary" link="{{ route('competenciasubcategoria.createdraft', [$competencia, $competenciaCategoria]) }}" 
                        onclick="window.location.href = this.getAttribute('link');">
                            Agregar nivel de participación
                        </button>            
                    @else
                        <div style="text-align: center;">
                            <a href="/subcategoria" style="font-size: 14px;">
                                <i>Aún no se ha creado ningún nivel de participación</i></a>
                        </div>
                    @endif 

                @elseif(0)
                    @if(0)
                    @elseif(Gate::allows('only-asesor'))
                        @if($competenciaCategoria->registro_personalizado)

                            @if (\Carbon\Carbon::parse($competenciaCategoria->inicio_registros) >= \Carbon\Carbon::now()->startOfDay() 
                                && \Carbon\Carbon::parse($competenciaCategoria->fin_registros) < \Carbon\Carbon::now()->endOfDay())

                                @if($cuposrestantes > 0 || $cupo_ilimitado == True)
                                    <button class="btn btn-primary" link="{{ route('competenciacategoria.create', $competencia) }}" 
                                    onclick="window.location.href = this.getAttribute('link');">
                                        Inscribirse
                                    </button> <!-- RUTA PENDIENTE PARA INSCRIPCIONES --> 
                                @else
                                    <div style="text-align: center;">
                                        <a style="font-size: 14px; color: #eb1616;">
                                            <i>Se han ocupado todos los cupos<br> 
                                                de inscripción disponibles</i></a>
                                    </div>
                                @endif

                            @elseif(\Carbon\Carbon::parse($competenciaCategoria->inicio_registros) < \Carbon\Carbon::now()->startOfDay())
                                <div style="text-align: center;">  
                                    <a style="font-size: 14px; color: #eb1616;">                          
                                        <i>Inscripciones Cerradas</i></a>
                                </div>
                            @elseif(\Carbon\Carbon::parse($competenciaCategoria->fin_registros) > \Carbon\Carbon::now()->endOfDay())                                               
                                <div style="text-align: center;">   
                                    <a style="font-size: 14px; color: #eb1616;">                         
                                        <i>Inscripciones Próximamente</i></a>
                                </div>
                            @endif                
                            
                        @elseif(!$competenciaCategoria->registro_personalizado)
                            @if(\Carbon\Carbon::parse($competencia->inicio_registros) >= \Carbon\Carbon::now()->startOfDay() 
                                && \Carbon\Carbon::parse($competencia->fin_registros) < \Carbon\Carbon::now()->endOfDay())

                                @if($cuposrestantes > 0 || $cupo_ilimitado == True)
                                    <button class="btn btn-primary" link="{{ route('competenciacategoria.create', $competencia) }}" 
                                    onclick="window.location.href = this.getAttribute('link');">
                                        Inscribirse
                                    </button> <!-- RUTA PENDIENTE PARA INSCRIPCIONES --> 
                                @else
                                    <div style="text-align: center;">
                                        <a style="font-size: 14px; color: #eb1616;">
                                            <i>Se han ocupado todos los cupos<br> 
                                                de inscripción disponibles</i></a>
                                    </div>
                                @endif
                            @elseif(\Carbon\Carbon::parse($competencia->inicio_registros) < \Carbon\Carbon::now()->startOfDay())                        
                                <div style="text-align: center;">  
                                    <a style="font-size: 14px; color: #eb1616;">                          
                                        <i>Inscripciones Cerradas</i></a>
                                </div>
                            @elseif(\Carbon\Carbon::parse($competencia->fin_registros) > \Carbon\Carbon::now()->endOfDay())
                                <div style="text-align: center;">   
                                    <a style="font-size: 14px; color: #eb1616;">                         
                                        <i>Inscripciones Próximamente</i></a>
                                </div>
                            @endif

                        @endif
                    @endif
                @endif
            @endif
        @endauth
    </div>

    <!--<div style="text-align: justify; text-justify: distribute-all-lines; display: flex; justify-content: center;">-->
    <div style="margin-bottom: 25px; text-align: justify;text-justify: distribute-all-lines;">
        <p style="width: 90%; text-align: justify;">{{ $categoria->descripcion }}</p>
    </div> 

    <div class="d-flex justify-content-between align-items-center" style="width: 90%; margin-bottom: 25px; display: flex; flex-wrap: wrap; gap: 15px; justify-content: center;">
        <div>
            <div style="margin-bottom: 15px; display: flex; flex-wrap: wrap; align-items: center;">
                <h5 style="margin-bottom: 0px;"> Inicio de Registros: </h5> 
                <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;"> 
                @if($competenciaCategoria->registro_personalizado)
                    {{ \Carbon\Carbon::parse($competenciaCategoria->inicio_registros)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }} 
                @else
                    {{ \Carbon\Carbon::parse($competencia->inicio_registros)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }} 
                @endif
                </p>
            </div>
            
            <div style="display: flex; flex-wrap: wrap; align-items: center;">
                <h5 style="margin-bottom: 0px;"> Cierre de Registros: </h5> 
                <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;"> 
                    @if($competenciaCategoria->registro_personalizado)
                        {{ \Carbon\Carbon::parse($competenciaCategoria->fin_registros)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }} 
                    @else
                        {{ \Carbon\Carbon::parse($competencia->fin_registros)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }} 
                    @endif
                </p>
            </div>
        </div>           
                
        @if(0)
        @if($competencia->publicada)
            <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
            onclick="window.location.href = '/';">
                @if($categoria->tipo == 'Equipos')
                    <b>Equipos Inscritos</b>
                @elseif($categoria->tipo == 'Proyectos')
                    <b>Proyectos Inscritos</b>
                @endif
            </button> <!-- Ruta pendiente -->
        @endif  
        @endif  

    </div>    


    <h2>Niveles de participación </h2>

    @if($competenciaSubcategorias->count() == 0) <!-- Si no hay competencia_categorias --> <!-- [!] Evaluar si las categorias registradas es igual al total de categorias y mostrar que no hya categorias nuevas por agregar -->
        @if(Gate::allows('only-superadmin')) 
            @if($subcategoriascount > 0)
                <p style="margin-left: 20px;"><i>Aún no se ha registrado ningún 
                <a href="/subcategoria" style="text-decoration: none;">nivel</a> de participación en esta categoría.</i></p>
            @else
                <p style="margin-left: 20px;"><i>Para registrar un nivel de participación primero debes agregarlo en el apartado de 
                <a href="/subcategoria" style="text-decoration: none;"><u>subcategorías</u></a>.</i></p>
            @endif
        @else
            <p style="margin-left: 20px;"><i>Próximamente podras ver el listado de niveles de participación en este apartado.</i></p>
        @endif
    @else <!-- Cuando hay categorias mostrarlas -->
        <!-- Contenedor con grid -->
        <div class="subcategorias-container" style="margin-top: 15px;  margin-bottom: 15px;">
            @foreach ($competenciaSubcategorias as $competenciaSubcategoria)            
                    <!--<div class="categorias-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;">-->
                    <div class="subcategorias-card">

                        <div class="text-center" style="margin-top: 0px;">                            
                            <b style="font-size: 25px;"><u>Nivel {{ $competenciaSubcategoria->nivel }}</u></b>                            
                        </div>

                        <div class="text-center" style="margin-top: 5px; margin-top: 0px;">                            
                            @if($competenciaSubcategoria->max_participantes == 1)
                                <h style="font-size: 15px;">(Individualmente)</h>
                            @else
                                <h style="font-size: 15px;">({{ $competenciaSubcategoria->min_participantes }} a {{ $competenciaSubcategoria->max_participantes }} participantes)</h>
                            @endif
                        </div>

                        <div class="text-center" style="margin-top: 10px; font-size: 16px;">
                            
                            @if($competenciaSubcategoria->costo_personalizado)
                                @if($competenciaSubcategoria->costo == 0)
                                    <b>Costo: </b>gratuito                                        
                                @else
                                    <b>Costo: </b>${{ number_format($competenciaSubcategoria->costo, 0, '.', ',') }}
                                    <div class="text-center" style="margin-top: 0px;">   
                                        <h style="font-size: 15px;">pesos mexicanos</h>
                                    </div>                              
                                @endif
                            @else     
                                @if($competenciaCategoria->costo == 0)
                                    <b>Costo: </b>gratuito                                          
                                @else                           
                                    <b>Costo: </b>${{ number_format($competenciaCategoria->costo, 0, '.', ',') }}

                                    <div class="text-center" style="margin-top: 0px;">   
                                        <h style="font-size: 15px;">pesos mexicanos</h>
                                    </div>
                                @endif                                
                            @endif

                            @if($competenciaSubcategoria->limite_inscripciones)
                                @if($competenciaSubcategoria->cuposrestantes > 0)
                                    <div class="text-center" style="margin-top: 0px;">   
                                        <h style="font-size: 15px;">Cupo limitado</h>
                                    </div>                                 
                                @else
                                    <b>Cupo de participación<br> lleno</br>
                                @endif
                            @endif
                        </div>                        

                        @auth <!-- PENDIENTE -->
                            @can('only-superadmin')
                                @if(!$competencia->enProgreso && !$competencia->pasada) 

                                    <div class="text-center" style="margin-top: 10px;">
                                        <!-- Botón para Editar -->
                                        <a href="{{ $competencia->publicada ? route('competenciasubcategoria.edit', [$competencia, $competenciaCategoria, $competenciaSubcategoria]) : route('competenciasubcategoria.editdraft', [$competencia, $competenciaCategoria, $competenciaSubcategoria]) }}" 
                                        onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                                        style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #434851; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                        title="Editar Nivel de Participación">                                
                                            <i class="fas fa-edit" style="font-size: 20px;"></i> <!-- Ícono de FontAwesome -->
                                        </a>    
                                        
                                        <form action="{{route('competenciasubcategoria.destroy', [$competencia, $competenciaCategoria, $competenciaSubcategoria])}}" method = "POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')                                        
                                            
                                            <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar este nivel de participación?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                                            style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                                            title="Eliminar Nivel de Participación">
                                                <i class="fas fa-trash" style="font-size: 20px;"></i> <!-- Ícono de FontAwesome -->
                                            </button>
                                        </form>   
                                    </div>

                                @endif
                            @endcan
                        @endauth
                    </div>
            @endforeach
        </div>
    @endif
    <br>

    

</x-plantilla-body>

<!-- Scripts -->
<script>
    // Mostrar el modal
    function showModal() {
        document.getElementById('imageModal').style.display = 'flex';
        document.body.classList.add('modal-open'); // Deshabilita el scroll
    }

    // Cerrar el modal
    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.body.classList.remove('modal-open'); // Reactiva el scroll
    }
</script>

</html>