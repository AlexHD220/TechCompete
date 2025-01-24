<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Competencia Eliminada | Categoría</title>

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


    <div style="margin-bottom: 15px;">
                                                 
        <div style="display: flex; gap: 6px;">
            <h1 style="margin-top: 8px;"><u>Competencia Eliminada</u></h1>
        </div>

        <div style="display: flex; gap: 6px;">
            <h2 style="margin-top: 8px;">{{$competencia->name}}</h2>  
        </div>

        <div style="display: flex; gap: 6px;">
            <h3 style="margin-top: 8px"> {{ $categoria -> name }}</h3> 
        </div>
            
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

    </div>    


    <h2>Niveles de participación </h2>

    @if($competenciaSubcategorias->count() == 0) <!-- Si no hay competencia_categorias --> <!-- [!] Evaluar si las categorias registradas es igual al total de categorias y mostrar que no hya categorias nuevas por agregar -->
        
        @if($subcategoriascount > 0)
            <p style="margin-left: 20px;"><i>Aún no se ha registrado ningún nivel de participación en esta categoría.</i></p>
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
                            @if(!$competenciaSubcategoria->limite_inscripciones || $competenciaSubcategoria->cuposrestantes > 0)
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
                                    <div class="text-center" style="margin-top: 0px;">   
                                        <h style="font-size: 15px;">Cupo limitado</h>
                                    </div> 
                                @endif
                            @else
                                <b>Cupo de participación<br> lleno</br>
                            @endif
                        </div>                                               
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