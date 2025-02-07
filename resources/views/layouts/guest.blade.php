<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--<title>{{ config('app.name', 'TechCompete') }}</title>-->

        <title>TechCompete</title>

        <!-- fevicon -->
       <link rel="icon" type="image/png" href="/dark/img/tsIcono.png"/>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">-->

        <!-- New Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">    
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"> 


        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <!-- Leaflet Control Geocoder CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <style>
            .go-home-link {
                position: fixed;
                top: 10px;
                right: 10px;
                font-size: 26px;
                text-decoration: none;
                color: white; /* Cambia el color a tu preferencia */
                margin-right: 20px;
            }

            .go-home-link:hover {
                color: gray; /* Color gris al pasar el mouse sobre el enlace */
            }

        </style>

        <style>
            #map {
                height: 300px; /* Ajusta el tamaño del mapa */
                width: 100%;
            }

            /* Pantallas pequeñas: celulares en orientación vertical */
            /*@media (max-width: 768px) {*/
            /*@media (max-width: 500px) {
                #map {
                    width: 100%;
                }
            }*/

            .leaflet-container {
                position: relative; /* Cambiar a relativa si la barra lateral está encima */
                z-index: 900; /* Mantener por detrás de la barra */
            }

            .leaflet-control-geocoder-form{
                color: black;                
            }

            .leaflet-control-geocoder-icon::before {
                content: "\f002"; /* Código Unicode del icono de búsqueda en Font Awesome */
                font-family: "Font Awesome 5 Free"; /* Especifica la fuente */
                font-weight: 900; /* Asegura que se use la versión sólida del icono */
                font-size: 16px; /* Ajusta el tamaño del icono */
                color: #333; /* Cambia el color del icono */
                display: inline-block; /* Asegura que se muestre correctamente */
                vertical-align: middle; /* Centra el icono verticalmente */
                padding-left: 4px /*10px;*/

                /*margin-left: auto; /* Empuja el ícono hacia la derecha */
                /*display: block;*/
                /*text-align: right;*/
                /*float: right;*/
            }

        </style>

        <style>
            /* Ocultar el input de tipo file */
            #imagen {
                display: none;
            }

            /* Estilo personalizado para el botón */
            .custom-file-label {
                display: inline-block;
                padding: 5px 10px;
                background-color: #0e2c6c;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                text-align: center;
                transition: background-color 0.3s ease;
            }

            .custom-file-label:hover {
                background-color: #061942;
            }

            /* Leyenda debajo del botón */
            .file-name {
                margin-top: 3px;
                font-size: 14px;
                color: #777;/*555*/
            }
        </style>

        <style>
            .error-message {
                /*color: red;*/
                color: #f87171;
                font-size: 14px;
                display: none; /* Oculto por defecto */
                margin-top: 10px;            
            }
        </style>

    </head>


    <body>
        
        @guest
            <!--Equis-->
            <a href="/" class="go-home-link">
                <i class="fas fa-times"></i>
            </a>
        @endguest

        <!-- mail-verificado-->
        @can('mail-verificado')
            <!--Equis-->
            <a href="/" class="go-home-link">
                <i class="fas fa-times"></i>
            </a>
        @endcan

        <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
            {{ $slot }}
        </div>
        
        <!-- Plantilla de Registro de Usuario Laravel -->

        @livewireScripts
        <script src="/dark/js/codigo.js" defer></script>
        
        <script src="/dark/js/confirmacionPassword.js" defer></script>
        <script src="/dark/js/confirmacionCorreo.js" defer></script>
    </body>
</html>
