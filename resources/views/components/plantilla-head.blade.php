<head>
    <meta charset="utf-8">
    
    {{ $slot }} <!--Title-->

    <!-- fevicon -->
    <link rel="icon" type="image/png" href="/dark/img/tsIcono.png"/>


    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <!--<link href="/dark/img/favicon.ico" rel="icon">-->

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- New Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"> 

    <!-- Libraries Stylesheet -->
    <link href="/dark/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/dark/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="/dark/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="/dark/css/style.css" rel="stylesheet">
    <link href="/dark/css/estilos.css" rel="stylesheet">

    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet Control Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    

    <style>
        .nav-link {
            display: flex; /* Activar Flexbox */
            align-items: center; /* Centrar los elementos verticalmente */
            justify-content: space-between; /* Asegura la separación entre texto e imagen */
        }

        .info-container {
            flex: 1; /* Ocupa todo el espacio disponible antes de la imagen */
            text-align: right; /* Alinear texto al lado izquierdo */
            word-break: break-word; /* Permitir saltos de línea si el texto es demasiado largo */
        }

        .profile-pic {
            flex-shrink: 0; /* Prevenir que la imagen se reduzca en pantallas pequeñas */
            margin-left: 15px; /* Espacio entre texto e imagen */
        }

    </style>

    <style>

        @media (max-width: 991px) {
            .ocultar-div-barra {
                display: none;
            }
            .mostrar-div-barra {
                margin: 30px;
            }
        }

    </style>

</head>