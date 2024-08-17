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

    </head>
    <body>
        
        <!--Equis-->
        <a href="/" class="go-home-link">
            <i class="fas fa-times"></i>
        </a>

        <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
        <script src="/dark/js/codigo.js"></script>
    </body>
</html>
