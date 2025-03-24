<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    
    <title>Asesor | Perfil</title>

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

        /* Leyenda debajo del botón */
        /*.file-name {
            margin-top: 5px;
            font-size: 14px;
            color: #555;            
        }*/
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
        
        @php
            session()->forget('alerta');
        @endphp
    @endif

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
    
    
    @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->            
        @can('only-asesor')                
            
            <div style="width: 100%; margin-bottom: 24px; display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 10px;">                
                <button style="margin-left: 10px;" class="btn btn-primary" link="{{ route('asesor.edit') }}" 
                onclick="window.location.href = this.getAttribute('link');">Editar Perfil</button>                
            </div>

        @endcan
    @endauth

    <div style="display: flex; align-items: center; margin-bottom: 25px;">
        
        <!-- Foto de perfil (solo si existe, sino genera imagen de color con inicial) -->
        <!--@if ($asesor->user->profile_photo_url !== 0)
        @endif-->

        <!-- Imagen de perfil -->
        @if (!is_null($asesor->user->profile_photo_path))
            <div>
                <img src="{{ $asesor->user->profile_photo_url }}" alt="{{ $asesor->user->name }}"style="height: 100px; width: 100px; margin-right: 15px; border-radius: 10px; object-fit: cover; cursor: pointer;" onclick="showPerfilModal()">
            </div>            
        @else        
            <form id="perfilImagenForm" action="{{ route('asesor.actualizarImagenPerfil') }}" method = "POST" enctype="multipart/form-data">

                @csrf <!--permite entrar al formulario muy importante agregar-->

                <input id="ruta" type="hidden" name="ruta" value="{{ request()->path() }}">

                <div class="text-center" style="display: flex; justify-content: center; align-items: center;">
                    <!-- Imagen de perfil -->                      
                    <label for="imagenPerfil" onMouseOver="this.style.backgroundColor='#bdbdbd'" onmouseout="this.style.backgroundColor='rgb(255 255 255 / 50%)'" style="height: 100px; width: 100px; margin-right: 15px; border-radius: 10px; cursor: pointer; background-color: rgb(255 255 255 / 50%); display: flex; justify-content: center; align-items: center;">
                        <div id="agregarImagenPerfil" style="align-items: center;" title="Agregar imagen de perfil">
                            <i style="color: black; font-size: 10px;" class="fa-solid fa-plus"></i>
                            <h style="color: black; text-align: center; margin-bottom: 0px; font-size: 14px;"><b> Agregar imagen de perfil </b></h>  
                        </div>                    
                                            
                        <div id="agregandoImagenPerfil" style="align-items: center; display: none;" >
                            <i style="color: black; font-size: 10px;" class="fa-solid fa-spinner"></i>
                            <h style="color: black; text-align: center; margin-bottom: 0px; font-size: 14px;"><b> Agregando imagen de perfil... </b></h>
                        </div> 
                    </label>

                    <input type="file" id="imagenPerfil" name="imagenPerfil" placeholder="imagenPerfil" accept=".png, .jpg, .jpeg" onchange="actualizarImagenPerfil(this)">

                    <!--<b><div id="file-name" class="file-name" style="margin-left: 10px;"></div></b>-->
                </div>  
            </form>
        @endif
                
        <div class="text-center" style="display: flex; flex-wrap: wrap; align-items: center;">
            <h1 style="margin-top: 8px; margin-bottom: 10px; margin-right: 10px;"> {{ $asesor -> name }}</h1> 
            <h1 style="margin-top: 8px; margin-bottom: 10px; margin-right: 10px;"> {{ $asesor -> lastname }}</h1>
        </div>
                
    </div>    
    
    <div style="margin-bottom: 35px;">           
    
        @if($asesor->institucion_id)              
            
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

            <div style="margin-left: 15px;">
                <p style="margin-bottom: 20px; font-size: 20px;">{{ $asesor->inst_nombre }}</p>   
            </div>

        @else
            <h4> Escuela: </h4>
            <button class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="margin-left: 15px; font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none; padding-top: 8px; padding-bottom: 8px;" 
            link="{{ route('asesor.vincularinstitucion') }}" 
            onclick="window.location.href = this.getAttribute('link');"><b>Vincular Institución</b></button>
        @endif
    </div> 
    

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

    <div id="div_contacto" style="margin-bottom: 25px;">
        <!--<h4 style="margin-top: 30px; margin-bottom: 15px;"> Informacion de contacto: </h4>-->
        <h4 style="margin-bottom: 0px; margin-bottom: 10px;"> Informacion de contacto: </h4>

        @if($asesor -> telefono)
            <div style="margin-left: 15px; margin-bottom: 10px; display: flex; flex-wrap: wrap; align-items: center;">
                <h5 style="margin-bottom: 0px;"> Telefono: </h5> 
                <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;">                 
                    <a target="_blank" href="tel:{{$asesor -> telefono}}">{{ $asesor -> telefono }}</a>
                </p>        
            </div>  
        @endif
        
        <div style="margin-left: 15px; margin-bottom: 10px; display: flex; flex-wrap: wrap; align-items: center;">
            <h5 style="margin-bottom: 0px;"> Correo electrónico: </h5> 
            <p style="margin-left: 5px; margin-bottom: 0px; font-size: 18px;"> 
                <a target="_blank" href="mailto:{{ $asesor -> email }}">{{ $asesor -> email }}</a>
            </p>        
        </div>                
    </div>

    <h4 style="margin-bottom: 0px; margin-bottom: 10px;"> Identificación: </h4>
    <!--<div style="display: flex; align-items: center; margin-bottom: 20px;">-->    
    <div style="margin-left: 15px;  margin-bottom: 30px;">
        <!-- Imagen de identificacion -->        
        <img class="sombra" src="{{ \Storage::url($asesor->identificacion_path) }}" alt="Credencial {{ $asesor->name }}" style="width: 50%; max-height: 300px; border-radius: 15px; object-fit: cover; cursor: pointer;" onclick="showCredencialModal()">
    </div>

    @if ($asesor->user->competencias->count() > 0)
        <br>
        <h3>Competencias</h3>

        <ul>
            @foreach($asesor->competencias as $competencia)
                <li>
                    <a onmouseover="this.style.color='white'" onmouseout="this.style.color='#6c7293'" href="{{route('competencia.show', $competencia)}}" style="text-decoration: none; color: inherit; display: inline-block;">
                        {{ $competencia -> nombre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif    

</x-plantilla-body>

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
        //let imagen = document.getElementById('imagen_portada');

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
                document.getElementById('agregandoImagenPerfil').style.display = 'block'; // Oculta el mensaje de error  

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

</html>