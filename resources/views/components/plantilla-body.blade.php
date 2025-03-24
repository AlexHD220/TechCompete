<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">

                <!--Imagen esquina superior dereha-->
                <div class="ocultar-div-barra navbar-brand mx-4 mb-3">
                    <a href="/">
                        <!--<h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>TechCompete</h3>--> 
                        <!--filter: brightness(100); background-color: white;-->
                        <img src="/dark/img/tsPortada.png" style="height: 60px; border-radius: 5px;">
                    </a>
                </div>

                <div class="mostrar-div-barra"></div>

                <!--etiqueta-->

                <!--<div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="/dark/img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Hola mundo</h6>
                        <span>Admin</span>
                    </div>
                </div>-->

                <div class="navbar-nav w-100">
                    <!--<a href="/dashboard" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>-->
                    
                    <!--<div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="cursor: pointer;">
                            <i class="fa fa-laptop me-2"></i>
                            Elements
                        </a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="button.html" class="dropdown-item">Buttons</a>
                            <a href="typography.html" class="dropdown-item">Typography</a>
                            <a href="element.html" class="dropdown-item">Other Elements</a>
                        </div>
                    </div>-->

                    <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/" class="nav-item nav-link"><i class="fa fa-home me-2"></i>Página principal</a>

                    @can('have-perfil')                                                                                        
                        <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="{{ Auth::user()->rol == 5 ? route('institucion.perfil') : (Auth::user()->rol == 6 ? route('asesor.perfil') : route('juez.perfil')) }}" class="nav-item nav-link"><i class="fa-solid fa-user me-2"></i>Perfil</a> 
                    @endcan

                    @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                        @can('only-user')
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="cursor: pointer;">
                                    <i class="far fa-file-alt me-2"></i>
                                    Navegación
                                </a>
                                <div class="dropdown-menu bg-transparent border-0">
                                    <!--<a href="/" class="dropdown-item">Página principal</a>-->
                                    <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/asesor" class="dropdown-item">Asesores</a>                        
                                    <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/equipo" class="dropdown-item">Equipos</a>
                                    <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/proyecto" class="dropdown-item">Proyectos</a>
                                    <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/participante" class="dropdown-item">Participantes</a>
                                    <!--<a href="/competencia" class="dropdown-item">Competencias</a>-->
                                </div>
                            </div>
                        @endcan
                    @endauth


                    @auth
                        @can('only-superadmin')
                            <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/administrador" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Administradores</a>
                            <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/staff" class="nav-item nav-link"><i class="fa-solid fa-clipboard-user me-2"></i>Staff</a>
                            <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/asesor/validarcuenta" class="nav-item nav-link"><i class="fa-solid fa-chalkboard-user me-2"></i>Asesores @if($NumcuentasAsesoresPendientes > 0) <span class="notification-bar-badge" style="margin-left: 15px;"><b>{{$NumcuentasAsesoresPendientes}}</b></span> @endif</a> <!-- {{ $NumcuentasAsesoresPendientes > 0 ? '('.$NumcuentasAsesoresPendientes.')' : '' }} -->
                            <!-- <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/juez" class="nav-item nav-link"><i class="fa-solid fa-clipboard-check me-2"></i>Jueces</a> -->
                            <div class="nav-item dropdown">
                                <div style="display: flex; justify-content: flex-start; margin-left: 5px;" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-clipboard-check me-2"></i>
                                    <a href="/juez" class="nav-link-dropdown" onmouseover="this.style.color='#eb1616'" onmouseout="this.style.color='#6c7293'" onclick="event.stopPropagation(); event.preventDefault(); window.location.href=this.href;">Jueces</a>                                    
                                </div>
                                
                                <div class="dropdown-menu bg-transparent border-0" style="margin-bottom: 5px; padding: 0px;">                                
                                    <a href="/registrojuez" style="margin-left: 5px; padding-left: 70px;" class="dropdown-item dropdown-border-item">
                                        <div style="display: flex; flex-wrap: wrap; align-items: center;">
                                            <i class="fa-solid fa-circle" style="font-size: 5px; margin-right: 10px;"></i>
                                            Códigos registro
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="nav-item dropdown">
                                <div style="display: flex; justify-content: flex-start; margin-left: 5px;" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-trophy me-2"></i>
                                    <a href="/competencia" class="nav-link-dropdown" onmouseover="this.style.color='#eb1616'" onmouseout="this.style.color='#6c7293'" onclick="event.stopPropagation(); event.preventDefault(); window.location.href=this.href;">Competencias</a>                                    
                                </div>                                
                                
                                <div class="dropdown-menu bg-transparent border-0" style="margin-bottom: 5px; padding: 0px;">                                
                                    <a href="/competencia/draft" style="margin-left: 5px; padding-left: 70px;" class="dropdown-item dropdown-border-item">
                                        <div style="display: flex; flex-wrap: wrap; align-items: center;">
                                            <i class="fa-solid fa-circle" style="font-size: 5px; margin-right: 10px;"></i>
                                            Borradores
                                        </div>
                                    </a>

                                    <a href="/competencia/historial" style="margin-left: 5px; padding-left: 70px;" class="dropdown-item dropdown-border-item">
                                        <div style="display: flex; flex-wrap: wrap; align-items: center;">
                                            <i class="fa-solid fa-circle" style="font-size: 5px; margin-right: 10px;"></i>
                                            Historial
                                        </div>
                                    </a>
                                </div>
                            </div>

                        @endcan
                    @endauth

                    @cannot('only-superadmin')
                        <!-- <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/competencia" class="nav-item nav-link"><i class="fa-solid fa-trophy me-2"></i>Competencias</a> -->

                        <div class="nav-item dropdown">
                            <div style="display: flex; justify-content: flex-start; margin-left: 5px;" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-trophy me-2"></i>
                                <a href="/competencia" class="nav-link-dropdown" onmouseover="this.style.color='#eb1616'" onmouseout="this.style.color='#6c7293'" onclick="event.stopPropagation(); event.preventDefault(); window.location.href=this.href;">Competencias</a>                                    
                            </div>                                
                            
                            <div class="dropdown-menu bg-transparent border-0" style="margin-bottom: 5px; padding: 0px;">
                                <a href="/competencia/historial" style="margin-left: 5px; padding-left: 70px;" class="dropdown-item dropdown-border-item">
                                    <div style="display: flex; flex-wrap: wrap; align-items: center;">
                                        <i class="fa-solid fa-circle" style="font-size: 5px; margin-right: 10px;"></i>
                                        Historial
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endcan

                    @auth
                        @can('only-superadmin')
                            <!-- <a style="display: flex; justify-content: flex-start; margin-left: 5px;" href="/categoria" class="nav-item nav-link"><i class="fa-solid fa-list me-2"></i>Categorías</a> -->

                            <div class="nav-item dropdown">
                                <div style="display: flex; justify-content: flex-start; margin-left: 5px;" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-list me-2"></i>
                                    <a href="/categoria" class="nav-link-dropdown" onmouseover="this.style.color='#eb1616'" onmouseout="this.style.color='#6c7293'" onclick="event.stopPropagation(); event.preventDefault(); window.location.href=this.href;">Categorías</a>                                    
                                </div>
                                
                                <div class="dropdown-menu bg-transparent border-0" style="margin-bottom: 5px; padding: 0px;">                                
                                    <a href="/subcategoria" style="margin-left: 5px; padding-left: 70px;" class="dropdown-item dropdown-border-item">
                                        <div style="display: flex; flex-wrap: wrap; align-items: center;">
                                            <i class="fa-solid fa-circle" style="font-size: 5px; margin-right: 10px;"></i>
                                            Subcategorías
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endcan
                    @endauth
                    <!--<a href="form.html" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Forms</a>-->
                    <!--<a href="" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Resultados</a>-->

                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                @if(0)
                    <a href="/" class="navbar-brand d-flex d-lg-none me-4">
                        <!--<h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>-->
                        <img src="/dark/img/tsPortada.png" style="height: 60px; border-radius: 5px;">
                    </a>
                @endif
                
                <a class="sidebar-toggler flex-shrink-0" style="cursor: pointer;">
                    <i class="fa fa-bars"></i>
                </a>

                @php
                    $previousUrl = session('_custom_previous.url');
                @endphp 

                @php                    
                    $respaldo = Session('_respaldo_previous.url'); 
                    $custom = Session('_current_custom_previous.url'); 
                @endphp     
                
                @if($previousUrl)
                    <button onclick="window.location.href='{{ $previousUrl }}'" type="submit" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='#eb1616';"  
                        style="margin-left: 15px; background-color: #eb1616; color: white; border: none; border-radius: 100%; 
                        width: 30px; height: 30px; display: inline-flex; justify-content: center; align-items: center;"
                        title="Regresar a la página anterior">
                            <i class="fa-solid fa-arrow-left" style="font-size: 20px;"></i> <!-- Ícono de FontAwesome -->
                    </button>
                @endif

                @if(0)
                    <b style="font-size: 10px;" title="_custom_previous">{{ $previousUrl }}</b>

                     | 
        
                    <h  style="font-size: 10px;" title="url()->previous();">{{ url()->previous(); }}</h>

                     |
        
                    <b style="font-size: 10px;" title="_respaldo_previous">{{ $respaldo }}</b>                    

                     |
        
                    <h style="font-size: 10px;" title="_current_custom_previous">{{ $custom }}</h>                    
                @endif


                @if(0)
                    <form class="d-none d-md-flex ms-4">
                        <input class="form-control bg-dark border-0" type="search" placeholder="Busqueda">
                        <button type="submit" class="btn btn-primary" style="margin-left: 15px; font-size: 15px;">Buscar</button>
                    </form>
                @endif

                <div class="navbar-nav align-items-center ms-auto">
                    
                    <!--User Menu-->
                    @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                        @include('parciales/user-menu')
                    @else
                        <div style="margin-top: 20px; margin-bottom: 20px;">
                            <a href="/login" style="font-size: 16px;"><b>Ingresar</b></a>
                        </div>
                    @endauth
                </div>


                <!--@guest este es el opuesto de auth cuando no estoy logueado muestrame esto
                @endguest-->


                
            </nav>
            <!-- Navbar End -->


            <!-- Sale & Revenue Start aqui esta mi contenido -->
            <div class="margenContenido">
                {{ $slot }}
            </div>
            <!-- Sale & Revenue End -->


            <!-- Sales Chart Start -->

            <!-- Sales Chart End -->


            <!-- Recent Sales Start -->

            <!-- Recent Sales End -->


            <!-- Widgets Start -->

            <!-- Widgets End -->


            <!-- Footer Start -->

            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <!--<a class="btn btn-lg btn-primary btn-lg-square back-to-top" id="backToTopButton" style="cursor: pointer; display: none;"><i class="bi bi-arrow-up"></i></a>--> <!--href="#"-->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/dark/lib/chart/chart.min.js"></script>
    <script src="/dark/lib/easing/easing.min.js"></script>
    <script src="/dark/lib/waypoints/waypoints.min.js"></script>
    <script src="/dark/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="/dark/lib/tempusdominus/js/moment.min.js"></script>
    <script src="/dark/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="/dark/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="/dark/js/main.js"></script>
    <script src="/dark/js/codigo.js" defer></script>
    <script src="/dark/js/notificacion.js"></script>
    <script src="/dark/js/fechas.js" defer></script>
    <script src="/dark/js/scripts.js" defer></script>
    
    <!--<script src="/dark/js/confirmacionPassword.js" defer></script>-->
    <!--<script src="/dark/js/confirmacionCorreo.js" defer></script>-->

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    


</body>