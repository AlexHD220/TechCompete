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

        #imagenCredencial {
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

    @if (!is_null($asesor->identificacion_path))
        <!-- Modal imagen Credencial -->
        <div id="imageCredencialModal" class="modal" style="display: none;">
            <span class="close" onclick="closeCredencialModal()">&times;</span>
            <div class="modal-overlay" onclick="closeCredencialModal()"></div>
            <img src="{{ \Storage::url($asesor->identificacion_path) }}" alt="{{ $asesor->name }}" class="modal-image" style="width: 90%;">
        </div>
    @endif

    @if (!is_null($asesor->user->profile_photo_path))
        <!-- Modal imagen perfil -->
        <div id="imagePerfilModal" class="modal" style="display: none;">
            <span class="close" onclick="closePerfilModal()">&times;</span>
            <div class="modal-overlay" onclick="closePerfilModal()"></div>
            <img src="{{ $asesor->user->profile_photo_url }}" alt="{{ $asesor->user->name }}" class="modal-image">
        </div>
    @endif        
            
    <h3 style="margin-bottom: 15px;"><b>Actualizar imagen de perfil</b></h3>

    <!-- Imagen de perfil -->
    @if (!is_null($asesor->user->profile_photo_path))
        <div>
            <img src="{{ $asesor->user->profile_photo_url }}" alt="{{ $asesor->user->name }}"style="height: 150px; width: 150px; border-radius: 10px; object-fit: cover; cursor: pointer;" onclick="showPerfilModal()">
        </div>     
        
        <div style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">             
            
            <form id="perfilImagenForm" action="{{ route('asesor.actualizarImagenPerfil') }}" method = "POST" enctype="multipart/form-data">

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

            <form id="eliminarPerfilImagenForm" action="{{ route('asesor.eliminarImagenPerfil') }}" method = "POST" enctype="multipart/form-data">
            
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
        <form id="perfilImagenForm" action="{{ route('asesor.actualizarImagenPerfil') }}" method = "POST" enctype="multipart/form-data">

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


    <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
    
    <h3 style="margin-top: 40px; margin-bottom: 15px;"><b>Actualizar credencial institucional o identificación oficial</b></h3>   

    <div>
        <!-- Imagen de Credencial -->        
        <img class="sombra" src="{{ \Storage::url($asesor->identificacion_path) }}" alt="Credencial {{ $asesor->name }}" style="width: 50%; max-height: 300px; border-radius: 15px; object-fit: cover; cursor: pointer;" onclick="showCredencialModal()">
    </div>

    <!--<label for="imagenCredencial" style="margin-top: 30px; margin-bottom: 5px;"><b> Actualizar imagen: </b></label>-->
    <div style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">             
        
        <form id="credencialForm" action="{{ route('asesor.actualizarCredencial') }}" method = "POST" enctype="multipart/form-data">

            @csrf <!--permite entrar al formulario muy importante agregar-->

            <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">
            
            <div style="display: flex; align-items: center;">
                <!-- Imagen de credencial -->        
                <label for="imagenCredencial" class="custom-file-label">
                    <div id="agregarImagenCredencial" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                        <div style="height: 21px;"><i style="font-size: 20px;" class=" fa-solid fa-panorama"></i></div>
                        Actualizar credencial 
                    </div>                    
                                        
                    <div id="agregandoImagenCredencial" style="flex-wrap: wrap; gap: 10px; align-items: center; display: none;" >
                        <div style="height: 21px;"><i style="font-size: 20px;" class="fa-solid fa-spinner"></i></div>
                        Actualizando credencial 
                    </div> 
                </label>
            </div>

            <input type="file" id="imagenCredencial" name="imagenCredencial" accept=".png, .jpg, .jpeg" onchange="actualizarImagenCredencial(this)">

            <!--<b><div id="file-name" class="file-name" style="margin-left: 10px;"></div></b>-->            
        </form>
        
    </div>


    <!-- ========================================================================================================================================================= -->
    
    <br>
    <div style="margin-top: 15px;">           

    @if($asesor->institucion_id)              
            
            <h4> Institución: </h4> 
                
            <p style="margin-left: 15px; font-size: 20px;"><b>{{ $asesor->institucion->name }}</b></p>              
            

            <form action="{{route('asesor.desvincularinstitucion', $asesor)}}" method = "POST" style="display: inline-block;">
                @csrf
                @method('DELETE')

                <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar la relacion con la institucion?')" class="btn btn-primary" onMouseOver="this.style.backgroundColor='#ba1313'" onmouseout="this.style.backgroundColor='#f40b0b'" style="margin-left: 15px; font-size: 14px; background-color: #f40b0b; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;">
                <b>Desvincular institucion</b></button>
            </form>

            @if(0)
                <div style="margin-bottom: 15px; display: flex; flex-wrap: wrap; align-items: center;">
                    <h4> Institución: </h4>                                 
                    
                    @auth
                        @can('only-asesor')
                            <div class="text-center" style="margin-top: 10px;">
                                
                                <!-- Botón para Editar -->
                                <a href="{{ route('asesor.editarinstitucion') }}" onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                                style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #434851; color: white; border: none; padding: 5px 10px; border-radius: 5px; display: inline-flex; justify-content: center; align-items: center;"
                                title="Editar Institución">                                
                                    <i class="fas fa-edit" style="font-size: 18px; margin-right: 5px;"></i> <!-- Ícono de FontAwesome -->
                                    Editar
                                </a> 

                            </div>
                        @endcan
                    @endauth

                </div>

                <div style="margin-left: 15px;">
                    <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('institucion.show', $asesor->institucion_id)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                            <b style="font-size: 20px;">{{ $asesor->institucion_id }}</b>
                    </a>
                </div>
            @endif

        @elseif($asesor->inst_independiente)
            <div style="margin-bottom: 15px; display: flex; flex-wrap: wrap; align-items: center;">
                <h4> Institución: </h4>  

                @auth
                    @can('only-asesor')
                        <div class="text-center" style="margin-top: 10px;">
                            <!-- Botón para Editar -->
                            <a href="{{ route('asesor.editarinstitucion') }}" onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                            style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #434851; color: white; border: none; padding: 5px 10px; border-radius: 5px; display: inline-flex; justify-content: center; align-items: center;"
                            title="Editar Institución">                                
                                <i class="fas fa-edit" style="font-size: 18px; margin-right: 5px;"></i> <!-- Ícono de FontAwesome -->
                                Editar
                            </a>    
                        </div>
                    @endcan
                @endauth
            </div>

            <div>
                <p style="margin-bottom: 20px; font-size: 20px;">{{ $asesor->inst_nombre }}</p>   
            </div>

        @elseif($asesor->asesor_institucion_solicitud)

            <h4 style="margin-bottom: 20px;"> Intitución: </h4>
            <p style="margin-left: 15px;">Ya se ha enviado una solicitud a "<b><i>{{ $asesor->asesor_institucion_solicitud->institucion->name }}</i></b>" para vincular tu cuenta de asesor con la institución.</p>              

            <form action="{{route('asesor.cancelarsolicitudinstitucion', $asesor->asesor_institucion_solicitud)}}" method = "POST" style="display: inline-block;">
                @csrf
                @method('DELETE')

                <button type="submit" onclick="return confirm('¿Está seguro que desea cancelar la solicitud de vinculacion de perfiles?')" class="btn btn-primary" onMouseOver="this.style.backgroundColor='#ba1313'" onmouseout="this.style.backgroundColor='#f40b0b'" style="margin-left: 15px; font-size: 14px; background-color: #f40b0b; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;">
                <b>Cancelar Solicitud</b></button>
            </form>

        @else
            <h4 style="margin-bottom: 20px;"> Intitución: </h4>
            <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="margin-left: 15px; font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
            link="{{ route('asesor.vincularinstitucion') }}" 
            onclick="window.location.href = this.getAttribute('link');"><b>Vincular Institución</b></button>
        @endif
    </div> 

    <!-- ========================================================================================================================================================= -->
    
    <h2 style="margin-top: 45px;" title="Nota: para guardar los cambios de esta sección, drigete al fondo de la página y presiona 'Actualizar perfil'.">
        <b>Actualizar información personal </b>
    </h2>

    <!--editar formulario por medio de la direccion de route:list, esto porque como no tengo un archivo, necesito mandar llamar a la ruta de la lista asesor.update-->
    <form id="registroForm" action="{{ route('asesor.perfilupdate')}}" method="post"  enctype="multipart/form-data"> <!--la diagonal me envia al principio de la url "solacyt.test/"-->
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

                
        <label for="name"><b> Nombre(s): </b></label>
        <input id="name" type="text" name="name" style="width: 250px" value = "{{ old('name') ?? $asesor -> name }}" minlength="3" maxlength="20" required><br><br> <!-- autocomplete="name" --->
        
        <label for="lastname"><b> Apellido(s): </b></label>
        <input id="lastname" type="text" name="lastname" style="width: 250px" value = "{{ old('lastname') ?? $asesor -> lastname }}" minlength="5" maxlength="30" required><br><br> <!-- autocomplete="name" --->
            
        <label for="email"><b> Correo electrónico: </b></label>
        <input id="email" type="email" name="email" style="width: 250px" value = "{{ old('email') ?? $asesor -> email }}" minlength="5" maxlength="50" required><br> <!-- autocomplete="username" -->
        
        <div id="email_confirmation_div" style="display: none">
            <br>
            <label for="email_confirmation"><b> Confirmar correo electrónico: </b></label>
            <input id="email_confirmation" type="email" name="email_confirmation" style="width: 250px" value = "{{ old('email_confirmation') ?? '' }}" minlength="5" maxlength="50" required disabled><!-- autocomplete="username" -->
            <small id="emailError" style="color: #f87171; display: none;"><div style="margin-top: 10px;"><b><i class="fa fa-exclamation-triangle"></i> Los correos electrónicos no coinciden.</b></div></small>
        </div>        
        

        <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->        


        <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->        

        <br>
        <div style="margin-top: 15px;">            
            
            <h3 style="margin-top: 15px; margin-bottom: 15px;"><b>Información de contacto</b></h3>

            <label for="telefono"><b> Número de telefono: </b></label>        
            <input id="telefono" type="tel" placeholder="Opcional" name="telefono" style="width: 250px" value = "{{ old('telefono') ?? $asesor -> telefono ?? '' }}" maxlength="15"><br><br> <!-- autocomplete="name" --->

            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
                @can('only-asesor')     
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; margin-bottom: 10px;">
                        <div style="height: 20px;">
                            <label class="switch">                            
                            <input type="checkbox" id="mostrar_contacto" name="mostrar_contacto" {{ old('mostrar_contacto') ? 'checked' : ($asesor->contacto_oculto ? '' : 'checked') }}>
                            <span class="slider"></span>
                            </label>
                        </div> 
                        
                        <label for = "mostrar_contacto"><b>Mostrar información de contacto </b></label>                                               
                    </div>    
                @endcan
            @endauth

        </div>

        <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->


        <div style="margin-top: 40px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">            
            <input type="submit" value="Actualizar perfil">                            
            <a href="{{ route('asesor.perfil') }}">Regresar</a>                    
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
        function showCredencialModal() {
            document.getElementById('imageCredencialModal').style.display = 'flex';
            document.body.classList.add('modal-open'); // Deshabilita el scroll
        }

        // Cerrar el modal
        function closeCredencialModal() {
            document.getElementById('imageCredencialModal').style.display = 'none';
            document.body.classList.remove('modal-open'); // Reactiva el scroll
        }
    </script>

    <script>
    
        const checkbox = document.getElementById('mostrar_contacto');             
            
        const divCredencial = document.getElementById('div_contacto');

        // Pagina recargada
        if(checkbox.checked){
            //divCredencial.classList.remove('disabled-input'); // Quitar opacidad del 50%
            divCredencial.classList.add('enabled-input'); // Aplicar opacidad del 100%
        }else{
            //divCredencial.classList.remove('enabled-input'); // Quitar opacidad del 50%
            divCredencial.classList.add('disabled-input'); // Aplicar opacidad del 100%
        }

        // Escuchar cambios en el checkbox
        checkbox.addEventListener('change', function () {
            if (this.checked) {                            
                divCredencial.classList.remove('disabled-input'); // Quitar opacidad del 50%
                divCredencial.classList.add('enabled-input'); // Aplicar opacidad del 100%
            } 
            else {
                divCredencial.classList.remove('enabled-input'); // Quitar opacidad del 100%
                divCredencial.classList.add('disabled-input'); // Aplicar opacidad del 50%
                
            }
        });
    </script>

    <script>
        document.getElementById('mostrar_contacto').addEventListener('change', function() {

            let switchInput = this;
        
            // Deshabilitar el switch inmediatamente para evitar cambios rápidos
            switchInput.disabled = true;

            //let mostrar = this.checked ? 1 : 0; // Convierte el valor a 1 o 0
            //let imagen = document.getElementById('imagen_credencial');

            // Oculta/Muestra la imagen sin recargar la página
            //imagen.style.display = mostrar ? 'block' : 'none';

            // Enviar el valor al backend usando Fetch API
            fetch("{{ route('asesor.ocultarContacto') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Token de seguridad de Laravel
                },
                body: JSON.stringify()
                /*{
                    contacto_oculto: mostrar
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

        function actualizarImagenCredencial() {
            var input = document.getElementById('imagenCredencial');
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
                    document.getElementById('agregarImagenCredencial').style.display = 'none'; // Oculta el mensaje de error  
                    document.getElementById('agregandoImagenCredencial').style.display = 'flex'; // Oculta el mensaje de error  

                    document.getElementById("credencialForm").submit(); // Envía el formulario.
                }
            }
        }

        /*function actualizarImagenCredencial() {
            var input = document.getElementById('imagenCredencial');
            var archivo = input.files[0];

            if (archivo) {
                document.getElementById('agregarImagenCredencial').style.display = 'none'; // Oculta el mensaje de error  
                document.getElementById('agregandoImagenCredencial').style.display = 'block'; // Oculta el mensaje de error  

                document.getElementById("credencialForm").submit(); // Envía el formulario.
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

        document.getElementById('eliminarImagenPerfilButton').addEventListener('click', function() {
            document.getElementById('eliminarImagenPerfil').style.display = 'none'; // Oculta el mensaje de error  
            document.getElementById('eliminandoImagenPerfil').style.display = 'flex'; // Oculta el mensaje de error  

            document.getElementById("eliminarPerfilImagenForm").submit(); // Envía el formulario.
        });

    </script>

    <script>
        const email = document.getElementById('email');
        const confirmEmail = document.getElementById('email_confirmation');
        const emailError = document.getElementById('emailError');

        const emailConfirmationDiv = document.getElementById('email_confirmation_div');

        if ("{{ $asesor->email }}" != email.value) {
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
            if ("{{$asesor->email }}" != email.value) {
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
            if ("{{$asesor->email }}" != email.value) {
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