<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Editar Perfil</title>

    <!-- Estilos -->
    <style>
        /* Estilo para el modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Imagen sin márgenes, centrada */
        .modal-image {
            height: 500px; /* Máxima altura de la imagen */
            object-fit: contain; /* Ajustar imagen sin recortes */
            max-width: 100%; /* Asegura que no se desborde horizontalmente */
            margin: 0; /* Sin márgenes adicionales */
            padding: 0;
        }

        /* Fondo difuminado */
        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px); /* Difumina el fondo */
            z-index: -1;
        }

        /* Botón para cerrar */
        .close {
            position: absolute;
            top: 5px;
            right: 25px;
            font-size: 45px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            z-index: 1001; /* Asegura que esté encima de la imagen */
        }

        /* Quitar scroll al mostrar el modal */
        body.modal-open {
            overflow: hidden;
        }
    </style>

    <style>
        .sombra {
            /*box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.5);*/
            box-shadow: 0px 0px 5px 3px rgba(255,255,255,0.2);
        }
    </style>


    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet Control Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <style>
        #map {
            height: 300px; /* Ajusta el tamaño del mapa */
            width: 60%;
        }

        /* Pantallas pequeñas: celulares en orientación vertical */
        /*@media (max-width: 768px) {*/
        @media (max-width: 500px) {
            #map {
                width: 100%;
            }
        }

        .leaflet-container {
            position: relative; /* Cambiar a relativa si la barra lateral está encima */
            z-index: 900; /* Mantener por detrás de la barra */
        }

    </style>

    <style>    
        .disabled-input {
            opacity: 0.3; /* Opacidad al 50% */
            pointer-events: none; /* Evita interacción cuando está deshabilitado */
        }

        
        .enabled-input {
            opacity: 1; /* Opacidad al 100% */
            pointer-events: auto; /* Permite interacción */
        }
    </style>

    <style>
        /* Ocultar el input de tipo file */
        #imagenPerfil {
            display: none;
        }

        #imagenPortada {
            display: none;
        }

        /* Estilo personalizado para el botón */
        .custom-file-label {
            display: inline-block;
            padding: 5px 10px;
            background-color: #e26b18;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .custom-file-label:hover {
            background-color: #bd4e00;
        }

        .custom-delete-label {
            display: inline-block;
            padding: 5px 10px;
            background-color: #eb1616;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .custom-delete-label:hover {
            background-color: #bd1616;
        }

        /* Leyenda debajo del botón */
        .file-name {
            margin-top: 5px;
            font-size: 14px;
            color: #555;            
        }
    </style>


</x-plantilla-head>

<x-plantilla-body>

    <!--@php
        $previousUrl = session('_custom_previous.url');
    @endphp-->

    
    <h1 style="margin-bottom: 30px;">Editar Perfil</h1>

    @if (!is_null($institucion->ubicacion_imagen))
        <!-- Modal imagen portada -->
        <div id="imagePortadaModal" class="modal" style="display: none;">
            <span class="close" onclick="closePortadaModal()">&times;</span>
            <div class="modal-overlay" onclick="closePortadaModal()"></div>
            <img src="{{ \Storage::url($institucion->ubicacion_imagen) }}" alt="{{ $institucion->name }}" class="modal-image" style="width: 90%;">
        </div>
    @endif

    @if (!is_null($institucion->user->profile_photo_path))
        <!-- Modal imagen perfil -->
        <div id="imagePerfilModal" class="modal" style="display: none;">
            <span class="close" onclick="closePerfilModal()">&times;</span>
            <div class="modal-overlay" onclick="closePerfilModal()"></div>
            <img src="{{ $institucion->user->profile_photo_url }}" alt="{{ $institucion->user->name }}" class="modal-image">
        </div>
    @endif    


    <h3 style="margin-bottom: 15px;"><b>Actualizar imagen de portada</b></h3>

    <!--<div style="display: flex; align-items: center; margin-bottom: 20px;">-->
    @if (!is_null($institucion->ubicacion_imagen))
        
        @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
            @can('only-institucion')                
                      
                <div style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                        <label for = "mostrar_portada"><b>Mostrar portada: </b></label>

                        <div style="height: 20px;">
                            <label class="switch">                            
                            <input type="checkbox" id="mostrar_portada" name="mostrar_portada" {{ old('mostrar_portada') ? 'checked' : ($institucion->portada_oculta ? '' : 'checked') }}>
                            <span class="slider"></span>
                            </label>
                        </div>                                
                    </div>                          
                </div>

            @endcan
        @endauth        
    

        <div id="div_portada">
            <!-- Imagen de portada -->        
            <img class="sombra" src="{{ \Storage::url($institucion->ubicacion_imagen) }}" alt="{{ $institucion->name }}" style="width: 50%; max-height: 200px; border-radius: 15px; object-fit: cover; cursor: pointer;" onclick="showPortadaModal()">
        </div>

        <!--<label for="imagenPortada" style="margin-top: 30px; margin-bottom: 5px;"><b> Actualizar imagen: </b></label>-->
        <div style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">             
            
            <form id="portadaForm" action="{{ route('institucion.actualizarPortada') }}" method = "POST" enctype="multipart/form-data">

                @csrf <!--permite entrar al formulario muy importante agregar-->

                <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">
                
                <div style="display: flex; align-items: center;">
                    <!-- Imagen de portada -->        
                    <label for="imagenPortada" class="custom-file-label">
                        <div id="agregarImagenPortada" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                            <div style="height: 21px;"><i style="font-size: 20px;" class=" fa-solid fa-panorama"></i></div>
                            Actualizar imagen 
                        </div>                    
                                            
                        <div id="agregandoImagenPortada" style="flex-wrap: wrap; gap: 10px; align-items: center; display: none;" >
                            <div style="height: 21px;"><i style="font-size: 20px;" class="fa-solid fa-spinner"></i></div>
                            Actualizando imagen 
                        </div> 
                    </label>
                </div>

                <input type="file" id="imagenPortada" name="imagenPortada" accept=".png, .jpg, .jpeg" onchange="actualizarImagenPortada(this)">

                <!--<b><div id="file-name" class="file-name" style="margin-left: 10px;"></div></b>-->            
            </form>

            <form id="eliminarPortadaForm" action="{{ route('institucion.eliminarPortada') }}" method = "POST" enctype="multipart/form-data">
            
                @csrf <!--permite entrar al formulario muy importante agregar-->

                <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">

                <div style="display: flex; align-items: center;">
                    <!-- Imagen de portada -->
                    <button id="eliminarPortadaButton" class="btn btn-primary" onMouseOver="this.style.backgroundColor='#bd1616'" onmouseout="this.style.backgroundColor='#eb1616'" style="font-size: 14px; background-color: #eb1616; border:0px; box-shadow: none;">
                        <div id="eliminarImagenPortada" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                            <div style="height: 20px;"><i style="font-size: 20px;" class="fa-solid fa-trash-can"></i></div>
                            Eliminar imagen 
                        </div>                    
                                            
                        <div id="eliminandoImagenPortada" style="flex-wrap: wrap; gap: 10px; align-items: center; display: none;" >
                            <div style="height: 21px;"><i style="font-size: 20px;" class="fa-solid fa-spinner"></i></div>
                            Eliminando imagen 
                        </div> 
                    </button>
                </div>
            </form>
        </div>        

    @else        
        <form id="portadaForm" action="{{ route('institucion.actualizarPortada') }}" method = "POST" enctype="multipart/form-data">

            @csrf <!--permite entrar al formulario muy importante agregar-->

            <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">
            
            <div>
                <!-- Imagen de portada -->        
                <label class="text-center" for="imagenPortada" class="sombra" onMouseOver="this.style.backgroundColor='#bdbdbd'" onmouseout="this.style.backgroundColor='rgb(255 255 255 / 50%)'" style="width: 50%; height: 200px; border-radius: 15px; cursor: pointer; background-color: rgb(255 255 255 / 50%); display: flex; justify-content: center; align-items: center;">
                    <div id="agregarImagenPortada" style="align-items: center;">
                        <i style="color: black; font-size: 25px; margin-bottom: 10px" class="fa-solid fa-plus"></i>
                        <h2 style="color: black; text-align: center; margin-bottom: 0px;"> Agregar imagen de portada </h2>  
                    </div>                    

                    <div id="agregandoImagenPortada" style="display: none;">
                        <div style="align-items: center;">
                            <i style="color: black; font-size: 25px; margin-bottom: 10px;" class="fa-solid fa-spinner"></i>
                            <h2 style="color: black; text-align: center; margin-bottom: 0px;"> Agregando imagen de portada... </h2>
                        </div> 
                    </div>
                </label>
            </div>

            <input type="file" id="imagenPortada" name="imagenPortada" accept=".png, .jpg, .jpeg" onchange="actualizarImagenPortada(this)">

            <!--<b><div id="file-name" class="file-name" style="margin-left: 10px;"></div></b>-->            
        </form>
    @endif    
        
    <h3 style="margin-top: 40px; margin-bottom: 15px;"><b>Actualizar imagen de perfil</b></h3>

    <!-- Imagen de perfil -->
    @if (!is_null($institucion->user->profile_photo_path))
        <div>
            <img src="{{ $institucion->user->profile_photo_url }}" alt="{{ $institucion->user->name }}"style="height: 150px; width: 150px; border-radius: 10px; object-fit: cover; cursor: pointer;" onclick="showPerfilModal()">
        </div>     
        
        <div style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">             
            
            <form id="perfilImagenForm" action="{{ route('institucion.actualizarImagenPerfil') }}" method = "POST" enctype="multipart/form-data">

                @csrf <!--permite entrar al formulario muy importante agregar-->

                <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">
                
                <div style="display: flex; align-items: center;">
                    <!-- Imagen de perfil -->        
                    <label for="imagenPerfil" class="custom-file-label">
                        <div id="agregarImagenPerfil" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                            <div style="height: 21px;"><i style="font-size: 20px;" class="fa-solid fa-image"></i></div>
                            Actualizar imagen 
                        </div>                    
                                            
                        <div id="agregandoImagenPerfil" style="flex-wrap: wrap; gap: 10px; align-items: center; display: none;" >
                            <div style="height: 21px;"><i style="font-size: 20px;" class="fa-solid fa-spinner"></i></div>
                            Actualizando imagen 
                        </div> 
                    </label>
                </div>

                <input type="file" id="imagenPerfil" name="imagenPerfil" accept=".png, .jpg, .jpeg" onchange="actualizarImagenPerfil(this)">

                <!--<b><div id="file-name" class="file-name" style="margin-left: 10px;"></div></b>-->            
            </form>

            <form id="eliminarPerfilImagenForm" action="{{ route('institucion.eliminarImagenPerfil') }}" method = "POST" enctype="multipart/form-data">
            
                @csrf <!--permite entrar al formulario muy importante agregar-->

                <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">

                <div style="display: flex; align-items: center;">
                    <!-- Imagen de perfil -->
                    <button id="eliminarImagenPerfilButton" class="btn btn-primary" onMouseOver="this.style.backgroundColor='#bd1616'" onmouseout="this.style.backgroundColor='#eb1616'" style="font-size: 14px; background-color: #eb1616; border:0px; box-shadow: none;">
                        <div id="eliminarImagenPerfil" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                            <div style="height: 20px;"><i style="font-size: 20px;" class="fa-solid fa-trash-can"></i></div>
                            Eliminar imagen 
                        </div>                    
                                            
                        <div id="eliminandoImagenPerfil" style="flex-wrap: wrap; gap: 10px; align-items: center; display: none;" >
                            <div style="height: 21px;"><i style="font-size: 20px;" class="fa-solid fa-spinner"></i></div>
                            Eliminando imagen 
                        </div> 
                    </button>
                </div>
            </form>
        </div>   
    @else        
        <form id="perfilImagenForm" action="{{ route('institucion.actualizarImagenPerfil') }}" method = "POST" enctype="multipart/form-data">

            @csrf <!--permite entrar al formulario muy importante agregar-->

            <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">

            <div>
                <!-- Imagen de perfil -->                      
                <label class="text-center" for="imagenPerfil" onMouseOver="this.style.backgroundColor='#bdbdbd'" onmouseout="this.style.backgroundColor='rgb(255 255 255 / 50%)'" style="height: 150px; width: 150px; margin-right: 15px; border-radius: 10px; cursor: pointer; background-color: rgb(255 255 255 / 50%); display: flex; justify-content: center; align-items: center;">
                    <div id="agregarImagenPerfil" style="align-items: center;" title="Agregar imagen de perfil">
                        <i style="color: black; font-size: 10px;" class="fa-solid fa-plus"></i>
                        <h style="color: black; text-align: center; margin-bottom: 0px; font-size: 14px;"><b> Agregar imagen<br>de perfil </b></h>  
                    </div>

                    <div id="agregandoImagenPerfil" style="display: none;">
                        <div style="align-items: center;">
                            <i style="color: black; font-size: 10px;" class="fa-solid fa-spinner"></i>
                            <h style="color: black; text-align: center; margin-bottom: 0px; font-size: 14px;"><b> Agregando imagen<br>de perfil... </b></h>
                        </div> 
                    </div>
                </label>

                <input type="file" id="imagenPerfil" name="imagenPerfil" placeholder="imagenPerfil" accept=".png, .jpg, .jpeg" onchange="actualizarImagenPerfil(this)">

                <!--<b><div id="file-name" class="file-name" style="margin-left: 10px;"></div></b>-->
            </div>  
        </form>
    @endif


    <!-- ========================================================================================================================================================= -->
    
    
    <h2 style="margin-top: 45px;" title="Nota: para guardar los cambios de esta sección, drigete al fondo de la página y presiona 'Actualizar perfil'.">
        <b>Actualizar información (formulario)</b>
    </h2>

    <!--editar formulario por medio de la direccion de route:list, esto porque como no tengo un archivo, necesito mandar llamar a la ruta de la lista asesor.update-->
    <form id="registroForm" action="{{ route('institucion.perfilupdate')}}" method="post"  enctype="multipart/form-data"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->
        <!--Mostrar errores-->
        @if ($errors->any())
            <div class="msgAlerta">                
                <ul>

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
        @method ('PATCH') <!--permite truquear nuestro formulario para editar la informacion-->

        <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">

        <h3 style="margin-top: 20px; margin-bottom: 15px;"><b>Información básica</b></h3>
                
        <label for="name"><b> Nombre de la institución: </b></label>
        <input id="name" type="text" name="name" style="width: 250px" value = "{{ old('name') ?? $institucion -> name }}" minlength="5" maxlength="100" required><br><br> <!-- autocomplete="name" --->
        
        <label for="tipo"><b> Tipo de institución educativa: </b></label>
        <input id="tipo" type="text" name="tipo" style="width: 250px" value = "{{ old('tipo') ?? $institucion -> tipo }}" required><br><br> <!-- autocomplete="name" --->
            
        <label for="email"><b> Correo electrónico: </b></label>
        <input id="email" type="email" name="email" style="width: 250px" value = "{{ old('email') ?? $institucion -> email }}" minlength="5" maxlength="50" required><br> <!-- autocomplete="username" -->
        
        <div id="email_confirmation_div" style="display: none">
            <br>
            <label for="email_confirmation"><b> Confirmar correo electrónico: </b></label>
            <input id="email_confirmation" type="email" name="email_confirmation" style="width: 250px" value = "{{ old('email_confirmation') ?? '' }}" minlength="5" maxlength="50" required disabled><!-- autocomplete="username" -->
            <small id="emailError" style="color: #f87171; display: none;"><div style="margin-top: 10px;"><b><i class="fa fa-exclamation-triangle"></i> Los correos electrónicos no coinciden.</b></div></small>
        </div>        
        

        <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->


        <br>
        <h3 style="margin-top: 30px; margin-bottom: 15px;"><b>Ubicación y domicilio</b></h3>

        <label for="pais"><b> País: </b></label>        
        <input id="pais" type="text" name="pais" style="width: 250px" value = "{{old('pais') ?? $institucion -> pais }}" required><br><br> <!-- autocomplete="name" --->
    
        <label for="estado"><b> Estado: </b></label>        
        <input id="estado" type="text" name="estado" style="width: 250px" value = "{{old('estado') ?? $institucion -> estado }}" required><br><br> <!-- autocomplete="name" --->
    
        <label for="ciudad"><b> Ciudad: </b></label>        
        <input id="ciudad" type="text" name="ciudad" style="width: 250px" value = "{{old('ciudad') ?? $institucion -> ciudad }}" required onblur="obtenerCoordenadasCiudad()"><br> <!-- autocomplete="name" --->
        <small id="error-ciudad" style="color: #f8b471; display: none;"><div style="margin-top: 10px;"><b><i class="fa fa-exclamation-circle"></i> Ciudad no encontrada. </b></div></small>
    
        <label for="domicilio" style="margin-top: 25px;"><b> Domicilio: </b></label>        
        <input id="domicilio" type="text" name="domicilio" style="width: 300px" value = "{{old('domicilio') ?? $institucion -> domicilio }}" required><br><br> <!-- autocomplete="name" --->        
    
        <div style="width: 60%; display: flex; justify-content: space-between; align-items: end; margin-bottom: 5px;">                        
            <!-- Texto 1 alineado a la izquierda -->
            <label for="map"><b> Ubicación: </b></label>                                      
            
            <!-- Texto 2 alineado a la derecha -->

            <a href="https://maps.google.com/intl/es/" style="font-size: 13px;" target="_blank" rel="noopener noreferrer" title="Apoyo de Búsqueda">
                Abrir Google Maps
            </a>
        </div>                

        <!-- Objeto debajo -->
        <div id="map" name="map"></div>                    
        <!--<small id="error-mapa" style="color: #f87171; display: none;"><div style="margin-top: 10px;"><b><i class="fa fa-exclamation-triangle"></i> Campo obligatorio, seleccione una ubicación en el mapa. </b></div></small>-->

        <input type="hidden" id="latitud" name="latitud" value = "{{ old('latitud') ?? $institucion -> latitud }}">
        <input type="hidden" id="longitud" name="longitud" value = "{{ old('longitud') ?? $institucion -> longitud }}">
        

        <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->


        <br>
        <h3 style="margin-top: 30px; margin-bottom: 15px;"><b>Información de contacto</b></h3>

        <label for="pagina_web"><b> Página Web: </b></label>        
        <input id="pagina_web" type="url" placeholder="Opcional" name="pagina_web" style="width: 250px" value = "{{ old('pagina_web') ?? $institucion -> pagina_web ?? '' }}" ><br><br> <!-- autocomplete="name" --->
    

        <label for="telefono"><b> Número de telefono: </b></label>        
        <input id="telefono" type="tel" placeholder="Opcional" name="telefono" style="width: 250px" value = "{{ old('telefono') ?? $institucion -> telefono ?? '' }}" maxlength="15"><br><br> <!-- autocomplete="name" --->
    

        <label for="whatsapp"><b> WhatsApp: </b></label>        
        <input id="whatsapp" type="tel"  placeholder="Opcional" name="whatsapp" style="width: 250px" value = "{{ old('whatsapp') ?? $institucion -> whatsapp ?? '' }}" maxlength="15"><br><br> <!-- autocomplete="name" --->
    

        <label for="email_contacto"><b> Correo elecrónico de contacto: </b></label>                           
        <input id="email_contacto" type="email" placeholder="Opcional" name="email_contacto" style="width: 250px" value = "{{ old('email_contacto') ?? $institucion -> email_contacto ?? '' }}" minlength="5" maxlength="50"><br><br> <!-- autocomplete="username" -->        


        <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

        
        <h3 style="margin-top: 30px; margin-bottom: 15px;">Credencial del estudiante</h3>

        <div>
            <label for="siNombreButton"><b> ¿La credencial del estudiante cuenta con el nombre de la institucion? </b></label>                    

            <div style="margin-top: 5px; display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
                <div style="align-items: center;">
                    <input id="siNombreButton" type="radio" value=1 name="nombre_escuela_credencial" required
                    {{ old('nombre_escuela_credencial') == 1 ? 'checked' : (($institucion -> nombre_escuela_credencial ?? '') == 1 ? 'checked' : '') }}> <!-- autocomplete="name" --->
                    <label for="siNombreButton" style="padding-top: 3px;">Si</label>                
                </div>        

                <div style="align-items: center;">
                    <input id="noNombreButton" type="radio" value=2 name="nombre_escuela_credencial" required
                    {{ old('nombre_escuela_credencial') == 2 ? 'checked' : (($institucion -> nombre_escuela_credencial ?? '') == 0 ? 'checked' : '') }}> <!-- autocomplete="name" --->
                    <label for="noNombreButton">No</label>
                </div>
            </div><br>
        </div>
        
        <div id="nombre_personalizado" style="display: none">            
            <label for="siEscritoButton"><b> ¿El nombre de la institucion está escrito de la siguiente forma? </b></label><br>
            "{{ $institucion -> name }}"

            <div style="margin-top: 5px; display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
                <div style="align-items: center;">
                    <input id="siEscritoButton" type="radio" value="1" name="nombre_escuela_personalizado" required disabled
                    {{ old('nombre_escuela_personalizado') == '1' ? 'checked' : (($institucion -> nombre_escuela_personalizado ?? '') == '0' ? 'checked' : '') }}> <!-- autocomplete="name" --->
                    <label for="siEscritoButton" style="margin-top: 3px;">Si</label>                
                </div>        

                <div style="align-items: center;">
                    <input id="noEscritoButton" type="radio" value="2" name="nombre_escuela_personalizado" required disabled
                    {{ old('nombre_escuela_personalizado') == '2' ? 'checked' : (($institucion -> nombre_escuela_personalizado ?? '') == '1' ? 'checked' : '') }}> <!-- autocomplete="name" --->
                    <label for="noEscritoButton">No</label>
                </div>
            </div><br>
        </div>
        
        <div id="escribir_nombre" style="display: none">            
            <label for="nombre_credencial_escrito"><b> ¿Cómo está escrito el nombre de la institucion ? </b></label>                    
            <input id="nombre_credencial_escrito" type="text" name="nombre_credencial_escrito" style="width: 250px" value = "{{ old('nombre_credencial_escrito') ?? $institucion -> nombre_credencial_escrito ?? '' }}" minlength="5" maxlength="100" required disabled><br><br> <!-- autocomplete="name" --->
        </div>

        <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->


        <div style="margin-top: 30px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">            
            <input type="submit" value="Actualizar perfil">                            
            <a href="{{ route('institucion.perfil') }}">Regresar</a>                    
        </div>

    </form>

    <!--<br>
    <button onclick="window.location.href = '/competencia';">Cancelar</button>-->
    
</x-plantilla-body>

<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->


    <!-- Scripts -->
    <script>
        // Mostrar el modal
        function showPerfilModal() {
            document.getElementById('imagePerfilModal').style.display = 'flex';
            document.body.classList.add('modal-open'); // Deshabilita el scroll
        }

        // Cerrar el modal
        function closePerfilModal() {
            document.getElementById('imagePerfilModal').style.display = 'none';
            document.body.classList.remove('modal-open'); // Reactiva el scroll
        }
    </script>

    <!-- Scripts -->
    <script>
        // Mostrar el modal
        function showPortadaModal() {
            document.getElementById('imagePortadaModal').style.display = 'flex';
            document.body.classList.add('modal-open'); // Deshabilita el scroll
        }

        // Cerrar el modal
        function closePortadaModal() {
            document.getElementById('imagePortadaModal').style.display = 'none';
            document.body.classList.remove('modal-open'); // Reactiva el scroll
        }
    </script>

    <script>
    
        const checkbox = document.getElementById('mostrar_portada');             
            
        const divPortada = document.getElementById('div_portada');

        // Pagina recargada
        if(checkbox.checked){
            //divPortada.classList.remove('disabled-input'); // Quitar opacidad del 50%
            divPortada.classList.add('enabled-input'); // Aplicar opacidad del 100%
        }else{
            //divPortada.classList.remove('enabled-input'); // Quitar opacidad del 50%
            divPortada.classList.add('disabled-input'); // Aplicar opacidad del 100%
        }

        // Escuchar cambios en el checkbox
        checkbox.addEventListener('change', function () {
            if (this.checked) {                            
                divPortada.classList.remove('disabled-input'); // Quitar opacidad del 50%
                divPortada.classList.add('enabled-input'); // Aplicar opacidad del 100%
            } 
            else {
                divPortada.classList.remove('enabled-input'); // Quitar opacidad del 100%
                divPortada.classList.add('disabled-input'); // Aplicar opacidad del 50%
                
            }
        });
    </script>

    <script>
        document.getElementById('mostrar_portada').addEventListener('change', function() {

            let switchInput = this;
        
            // Deshabilitar el switch inmediatamente para evitar cambios rápidos
            switchInput.disabled = true;

            //let mostrar = this.checked ? 1 : 0; // Convierte el valor a 1 o 0
            //let imagen = document.getElementById('imagen_portada');

            // Oculta/Muestra la imagen sin recargar la página
            //imagen.style.display = mostrar ? 'block' : 'none';

            // Enviar el valor al backend usando Fetch API
            fetch("{{ route('institucion.ocultarPortada') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Token de seguridad de Laravel
                },
                body: JSON.stringify()
                /*{
                    portada_oculta: mostrar
                })*/
            })        
            .then(data => {            
                // Después de 10 segundos se vuelve a habilitar el switch
                setTimeout(() => {
                    switchInput.disabled = false;
                }, 3000);
            })
            .catch(error => {            
                // En caso de error también se vuelve a habilitar el switch después de 10 segundos
                setTimeout(() => {
                    switchInput.disabled = false;
                }, 3000);
            });

            /*.then(response => response.json())
            .then(data => console.log(data.message))
            .catch(error => console.error('Error:', error));*/
        });
    </script>

    <script>

        function actualizarImagenPortada() {
            var input = document.getElementById('imagenPortada');
            var archivo = input.files[0];

            if (archivo) {
                var tipoImagen = archivo.type;
                if (tipoImagen !== 'image/png' && tipoImagen !== 'image/jpeg' && tipoImagen !== 'image/jpg') {        
                    input.value = ''; // Limpiar el input para permitir seleccionar otro archivo

                    // Usando SweetAlert para notificación
                    Swal.fire({
                        title: "¡Hubo un error!",
                        text: "Por favor, selecciona un archivo PNG o JPG válido.",
                        icon: "error",
                        //timer: 3000,
                        //showConfirmButton: false
                    });
                }
                else{
                    document.getElementById('agregarImagenPortada').style.display = 'none'; // Oculta el mensaje de error  
                    document.getElementById('agregandoImagenPortada').style.display = 'flex'; // Oculta el mensaje de error  

                    document.getElementById("portadaForm").submit(); // Envía el formulario.
                }
            }
        }

        /*function actualizarImagenPortada() {
            var input = document.getElementById('imagenPortada');
            var archivo = input.files[0];

            if (archivo) {
                document.getElementById('agregarImagenPortada').style.display = 'none'; // Oculta el mensaje de error  
                document.getElementById('agregandoImagenPortada').style.display = 'block'; // Oculta el mensaje de error  

                document.getElementById("portadaForm").submit(); // Envía el formulario.
            }
        }*/
        
    </script>

    <script>

        function actualizarImagenPerfil() {
            var input = document.getElementById('imagenPerfil');
            var archivo = input.files[0];

            if (archivo) {
                var tipoImagen = archivo.type;
                if (tipoImagen !== 'image/png' && tipoImagen !== 'image/jpeg' && tipoImagen !== 'image/jpg') {        
                    input.value = ''; // Limpiar el input para permitir seleccionar otro archivo

                    // Usando SweetAlert para notificación
                    Swal.fire({
                        title: "¡Hubo un error!",
                        text: "Por favor, selecciona un archivo PNG o JPG válido.",
                        icon: "error",
                        //timer: 3000,
                        //showConfirmButton: false
                    });
                }
                else{
                    document.getElementById('agregarImagenPerfil').style.display = 'none'; // Oculta el mensaje de error  
                    document.getElementById('agregandoImagenPerfil').style.display = 'flex'; // Oculta el mensaje de error  

                    document.getElementById("perfilImagenForm").submit(); // Envía el formulario.
                }
            }
        }
        
        /*function actualizarImagenPerfil() {
            var input = document.getElementById('imagenPerfil');
            var archivo = input.files[0];

            if (archivo) {
                document.getElementById('agregarImagenPerfil').style.display = 'none'; // Oculta el mensaje de error  
                document.getElementById('agregandoImagenPerfil').style.display = 'block'; // Oculta el mensaje de error  

                document.getElementById("perfilImagenForm").submit(); // Envía el formulario.
            }
        }*/
        
    </script>

    <script>

        document.getElementById('eliminarPortadaButton').addEventListener('click', function() {
            document.getElementById('eliminarImagenPortada').style.display = 'none'; // Oculta el mensaje de error  
            document.getElementById('eliminandoImagenPortada').style.display = 'flex'; // Oculta el mensaje de error  

            document.getElementById("eliminarPortadaForm").submit(); // Envía el formulario.
        });

    </script>

<script>

    document.getElementById('eliminarImagenPerfilButton').addEventListener('click', function() {
        document.getElementById('eliminarImagenPerfil').style.display = 'none'; // Oculta el mensaje de error  
        document.getElementById('eliminandoImagenPerfil').style.display = 'flex'; // Oculta el mensaje de error  

        document.getElementById("eliminarPerfilImagenForm").submit(); // Envía el formulario.
    });

</script>


    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Leaflet Control Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>

        // Obtener las coordenadas iniciales desde los inputs ocultos
        var initialLat = document.getElementById('latitud').value;
        var initialLng = document.getElementById('longitud').value;


        // Configurar el mapa centrado en la ubicación almacenada o en una ubicación predeterminada
        var mapCenter = initialLat || initialLng ? [initialLat, initialLng] : [20.6720375, -103.338396];
        
        var map = L.map('map',{
            scrollWheelZoom: false, // Deshabilita el zoom con la rueda del ratón
            zoomControl: false, // Desactivar el control de zoom predeterminado
            attributionControl: false, // Deshabilita los créditos del mapa.
            preferCanvas: true, // Optimizar mapa en celulares
        }).setView(mapCenter, 11); // 11 --> Zoom

        // Inicializar el mapa centrado en Guadalajara (Original)        
        /*var map = L.map('map', {
            scrollWheelZoom: false // Deshabilita el zoom con la rueda del ratón
        }).setView([20.659698, -103.349609], 10);*/ // 10 --> Zoom  


        // Cargar las capas del mapa desde OpenStreetMap
        /*L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            //maxZoom: 18, // Zoom maximo del mapa
            //attribution: '© OpenStreetMap contributors'
        }).addTo(map);*/


        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);


        // Variables para marcador y coordenadas iniciales
        var marker = null; // No se crea un marcador inicialmente              
        var latitudeInput = document.getElementById('latitud');
        var longitudeInput = document.getElementById('longitud');

         // Si hay coordenadas iniciales, ajustar el mapa al área encontrada
        if (initialLat || initialLng) {
            
            // Almacenar coordenadas de la ubicación
            var latLng = L.latLng(initialLat, initialLng);

            // Crear un "bounding box" alrededor de las coordenadas para ajustar el zoom
            var bounds = latLng.toBounds(500); // 500 metros alrededor del punto

            // Ajustar el mapa al área
            map.fitBounds(bounds);

            // Crear el marcador en la ubicación inicial
            marker = L.marker(latLng, { draggable: true }).addTo(map);
            
            // Evento para actualizar coordenadas al arrastrar el marcador
            marker.on('dragend', function (event) {
                var position = marker.getLatLng();
                latitudeInput.value = position.lat;
                longitudeInput.value = position.lng;
                verificarCampos(); // Verificar los campos después de actualizar los valores
            });            
        }
        

        // Actualizar marcador al hacer clic en el mapa
        map.on('click', function (e) {
            if (!marker) {
                // Crear marcador si no existe
                marker = L.marker(e.latlng, { draggable: true }).addTo(map);
                
                // Evento para actualizar coordenadas al arrastrar el marcador
                marker.on('dragend', function (event) {
                    var position = marker.getLatLng();
                    latitudeInput.value = position.lat;
                    longitudeInput.value = position.lng;
                    verificarCampos(); // Verificar los campos después de actualizar los valores
                });
            }

            // Actualizar la posición del marcador
            marker.setLatLng(e.latlng);
            latitudeInput.value = e.latlng.lat;
            longitudeInput.value = e.latlng.lng;
            verificarCampos(); // Verificar los campos después de actualizar los valores
        });

        // Agregar el control de búsqueda con autocompletado
        var geocoder = L.Control.geocoder({
            defaultMarkGeocode: false, // No marca automáticamente el lugar, solo muestra sugerencias
            placeholder: 'Buscar (Enter).', // Texto de la caja de búsqueda
            collapsed: true, // No colapsar el buscador
            
            resultsLimit: 4, // Limitar a 5 resultados 
            showResultIcons: true, // Mostrar íconos para los resultados                     
            autoComplete: true, // Activar autocompletado              
        })
        .on('markgeocode', function (e) {
            var bbox = e.geocode.bbox;
            var center = e.geocode.center;

            // Ajustar el mapa al área encontrada
            map.fitBounds([
                [bbox.getSouthWest().lat, bbox.getSouthWest().lng],
                [bbox.getNorthEast().lat, bbox.getNorthEast().lng]
            ]);

            if (!marker) {
                // Crear marcador si no existe
                marker = L.marker(center, { draggable: true }).addTo(map);

                marker.on('dragend', function (event) {
                    var position = marker.getLatLng();
                    latitudeInput.value = position.lat;
                    longitudeInput.value = position.lng;
                    verificarCampos(); // Verificar los campos después de actualizar los valores
                });
            }

            // Mover el marcador a la ubicación seleccionada
            marker.setLatLng(center);
            latitudeInput.value = center.lat;
            longitudeInput.value = center.lng;
            verificarCampos(); // Verificar los campos después de actualizar los valores
        })
        .addTo(map);


        // Personalizar el control de zoom
        L.control.zoom({
            position: 'bottomleft', // Colocar en la parte superior derecha
            zoomInText: '+', // Texto del botón de acercar
            zoomOutText: '-', // Texto del botón de alejar
            zoomInTitle: 'Acercar', // Título del botón de acercar
            zoomOutTitle: 'Alejar' // Título del botón de alejar
        }).addTo(map);
        

        const prevLat = 20.6720375;
        const prevLon = -103.338396;

        // Función para buscar coordenadas de la ciudad
        async function obtenerCoordenadasCiudad() {
            const city = document.getElementById("ciudad").value.trim(); // Capturar la ciudad ingresada
            
            if (!city) {
                return; // No hacer nada si el valor está vacío  
                errorCiudad.style.display = 'none'; // oculta el mensaje de error
            }

            var errorCiudad = document.getElementById('error-ciudad');
            
            // Consulta a la API de Nominatim
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${city}`);
            const data = await response.json();

            if (data.length === 0) {
                // Si no se encuentra la ciudad
                //alert("Ciudad no válida. Por favor, escribe una ciudad existente.");                
                errorCiudad.style.display = 'block'; // Muestra el mensaje de error
                return;
            }

            errorCiudad.style.display = 'none'; // oculta el mensaje de error

            // Recuperar coordenadas del primer resultado
            const lat = data[0].lat;
            const lon = data[0].lon;
            
            // Actualizar el centro del mapa
            map.setView([lat, lon], 11);  // 11 --> Zoom

            if(lat != prevLat && lon != prevLon){
                if (marker) {
                    map.removeLayer(marker);
                    marker = null; // Reiniciar la variable
                    latitudeInput.value = "";
                    longitudeInput.value = "";
                }

                prevLat = data[0].lat;
                prevLon = data[0].lon;
            }                

            /*try {
                // Opcional: Añadir marcador
                //L.marker([lat, lon]).addTo(map).bindPopup(`Ciudad: ${city}`).openPopup();
            } catch (error) {
                /*console.error("Error al buscar la ciudad:", error);
                alert("Ocurrió un error al buscar la ciudad.");
            }*/
        }

        //-------------------------------------------------------------> Cambiar ciudad automaticamente con autorrelleno

        /*// Detectar cambios en el input (autorrelleno o pérdida de foco)
        const inputCiudad = document.getElementById("ciudad");
        let previousValueCiudad = inputCiudad.value; // Guardar el valor inicial

        // Detectar pérdida de foco
        inputCiudad.addEventListener("blur", () => {
            if (inputCiudad.value !== previousValueCiudad) {
                // Si el valor cambió, actualizamos
                previousValueCiudad = inputCiudad.value;                
            }
        });

        // Usar MutationObserver para detectar cambios automáticos (sin foco)
        const observer = new MutationObserver(() => {
            if (inputCiudad.value !== previousValueCiudad) {
                // Si el valor cambió, actualizamos
                previousValueCiudad = inputCiudad.value;
                obtenerCoordenadasCiudad();
            }
        });

        // Configuración del MutationObserver
        observer.observe(inputCiudad, {
            attributes: true, // Observa cambios en atributos
            attributeFilter: ["value"], // Solo el atributo 'value'
        });*/

        //-------------------------------------------------------------> Delay entre cada busqueda de API

        /*// Crear función con debounce (espera 1s entre pulsaciones)
        const debounceFunction = debounce(fetchResults, 1000);

        // Evento de entrada en el campo de búsqueda
        document.getElementById("ciudad").addEventListener('input', (event) => {
            debounceFunction(event.target.value); // Llamar con debounce
        });*/

        /*function debounce(func, delay) {
            let timeoutId;

            return function (...args) {
                clearTimeout(timeoutId); // Elimina cualquier temporizador activo
                timeoutId = setTimeout(() => {
                func.apply(this, args); // Llama a la función original después del retraso
                }, delay);
            };
        }*/



        //=============================================================> Mensaje de error [INACTIVO]
        
        /*// Ocultar el mensaje de error cuando ambos valores sean válidos
        function verificarCampos() {
            if (latitudeInput.value || longitudeInput.value) {
                document.getElementById('error-mapa').style.display = 'none'; // Ocultar el mensaje de error
            }
        }

        document.getElementById('registroForm').addEventListener('submit', function (event) {
            var latitude = document.getElementById('latitud');
            var longitude = document.getElementById('longitud');

            // Validar si están vacíos
            if (!latitude.value && !longitude.value) {          
                event.preventDefault(); // Evita que el formulario se envíe
                document.getElementById('error-mapa').style.display = 'block'; // Mostrar el mensaje de error
            } 
            
        });*/
    </script>
    
    <script>
        // Referencias a inputs
        const NombreButtonSi = document.getElementById('siNombreButton');
        const NombreButtonNo = document.getElementById('noNombreButton');                     

        const EscritoButtonSi = document.getElementById('siEscritoButton');
        const EscritoButtonNo = document.getElementById('noEscritoButton');

        const nombreCredencialEscrito = document.getElementById('nombre_credencial_escrito');   

        // Referencias div    
        const nombrePersonalizado = document.getElementById('nombre_personalizado');

        const escribirNombre = document.getElementById('escribir_nombre');    

        // Pagina recargada
        if(NombreButtonSi.checked){
            EscritoButtonSi.removeAttribute('disabled'); // Habilitar el input   
            EscritoButtonNo.removeAttribute('disabled'); // Habilitar el input   
            
            nombrePersonalizado.style.display = 'block'; // Muestra el input

            // Pagina recargada
            if(EscritoButtonNo.checked){
                nombreCredencialEscrito.removeAttribute('disabled'); // Habilitar el input   
                
                escribirNombre.style.display = 'block'; // Muestra el input
            }
            else if(EscritoButtonSi.checked){
                escribirNombre.style.display = 'none'; // Oculta el input

                nombreCredencialEscrito.setAttribute('disabled', 'true'); // Deshabilitar el input                
                nombreCredencialEscrito.value = ""; // Limpia el valor de fecha_inicio  
            }
        }
        else if(NombreButtonNo.checked){
            nombrePersonalizado.style.display = 'none'; // Oculta el input
            escribirNombre.style.display = 'none'; // Oculta el input

            EscritoButtonSi.setAttribute('disabled', 'true'); // Deshabilitar el input                
            EscritoButtonSi.checked = ""; // Limpia el valor de fecha_inicio     
            
            EscritoButtonNo.setAttribute('disabled', 'true'); // Deshabilitar el input                
            EscritoButtonNo.checked = ""; // Limpia el valor de fecha_inicio                     

            nombreCredencialEscrito.setAttribute('disabled', 'true'); // Deshabilitar el input                
            nombreCredencialEscrito.value = ""; // Limpia el valor de fecha_inicio  
        }

        // ------------------------------------------------------------------------------>

        // Escuchar cambios en el radio button Si
        NombreButtonSi.addEventListener('change', function () {
            if (this.checked) {
                EscritoButtonSi.removeAttribute('disabled'); // Habilitar el input   
                EscritoButtonNo.removeAttribute('disabled'); // Habilitar el input   
                
                nombrePersonalizado.style.display = 'block'; // Muestra el input
            } 
        });

        // Escuchar cambios en el radio button No
        NombreButtonNo.addEventListener('change', function () {
            if (this.checked) {
                nombrePersonalizado.style.display = 'none'; // Oculta el input
                escribirNombre.style.display = 'none'; // Oculta el input

                EscritoButtonSi.setAttribute('disabled', 'true'); // Deshabilitar el input                
                EscritoButtonSi.checked = ""; // Limpia el valor de fecha_inicio     
                
                EscritoButtonNo.setAttribute('disabled', 'true'); // Deshabilitar el input                
                EscritoButtonNo.checked = ""; // Limpia el valor de fecha_inicio                     

                nombreCredencialEscrito.setAttribute('disabled', 'true'); // Deshabilitar el input                
                nombreCredencialEscrito.value = ""; // Limpia el valor de fecha_inicio  
            }
        });

        // ------------------------------------------------------------------------------>
        
        // Escuchar cambios en el radio button Si
        EscritoButtonNo.addEventListener('change', function () {
            if (this.checked) {
                nombreCredencialEscrito.removeAttribute('disabled'); // Habilitar el input                   

                escribirNombre.style.display = 'block'; // Muestra el input
            } 
        });

        // Escuchar cambios en el radio button No
        EscritoButtonSi.addEventListener('change', function () {
            if (this.checked) {
                escribirNombre.style.display = 'none'; // Oculta el input

                nombreCredencialEscrito.setAttribute('disabled', 'true'); // Deshabilitar el input                
                nombreCredencialEscrito.value = ""; // Limpia el valor de fecha_inicio                
            }
        });
    </script>

    <script>
        const email = document.getElementById('email');
        const confirmEmail = document.getElementById('email_confirmation');
        const emailError = document.getElementById('emailError');

        const emailConfirmationDiv = document.getElementById('email_confirmation_div');

        if ("{{ $institucion->email }}" != email.value) {
            emailConfirmationDiv.style.display = 'block';
            confirmEmail.removeAttribute('disabled'); // Habilitar el input
            
            if (confirmEmail.value != email.value) {
                emailError.style.display = 'block';
                confirmEmail.setCustomValidity('Los correos electrónicos no coinciden.'); // Actualiza el mensaje de validación
            }
        }
        else{
            emailConfirmationDiv.style.display = 'none'; 
            confirmEmail.setAttribute('disabled', 'true'); // Deshabilitar el input
            confirmEmail.value = '';

            emailError.style.display = 'none';        
            confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
        }

        document.getElementById('registroForm').addEventListener('submit', function (event) {
            if ("{{$institucion->email }}" != email.value) {
                if (email.value !== confirmEmail.value) {
                    event.preventDefault(); // Evita que el formulario se envíe
                    emailError.style.display = 'block'; // Muestra el mensaje de error
                    confirmEmail.setCustomValidity('Los correos electrónicos no coinciden.'); // Establece un mensaje de validación
                } else {
                    emailError.style.display = 'none'; // Oculta el mensaje de error
                    confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
                }
            }                     
        });
        

        confirmEmail.addEventListener('input', function () {
            if(confirmEmail.value){    
                if (confirmEmail.value === email.value) {
                    emailError.style.display = 'none';
                    confirmEmail.setCustomValidity(''); // Limpia el mensaje si coinciden
                } else {
                    emailError.style.display = 'block';
                    confirmEmail.setCustomValidity('Los correos electrónicos no coinciden.'); // Actualiza el mensaje de validación
                }
            }
            else{
                emailError.style.display = 'none';        
                confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
            }
        });

        email.addEventListener('input', function () {
            if ("{{$institucion->email }}" != email.value) {
                emailConfirmationDiv.style.display = 'block';
                confirmEmail.removeAttribute('disabled'); // Habilitar el input
            }
            else{
                emailConfirmationDiv.style.display = 'none'; 
                confirmEmail.setAttribute('disabled', 'true'); // Deshabilitar el input
                confirmEmail.value = ''; 

                emailError.style.display = 'none';        
                confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
            }

            if(confirmEmail.value){
                if (confirmEmail.value === email.value) {
                    emailError.style.display = 'none';
                    confirmEmail.setCustomValidity(''); // Limpia el mensaje si coinciden
                } else {
                    emailError.style.display = 'block';
                    confirmEmail.setCustomValidity('Los correos electrónicos no coinciden.'); // Actualiza el mensaje de validación
                }
            }
            else{
                emailError.style.display = 'none';        
                confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
            }
        });

    </script>

</html>