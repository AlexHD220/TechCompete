                <!--<div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="/dark/img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="/dark/img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="/dark/img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificatin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div>-->
                    
                    <!--User Menu-->
                    <div class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-lg-inline-flex me-lg-2"><b>{{ auth()->user()->name }}</b></span> <!--{--class = "d-none" Hacer que se esconda cuando se hace pequeña la pagina--}-->
                            @can('only-admin')
                                @if(Auth::id() == 1)  
                                    <span class="d-lg-inline-flex me-lg-2"><b>(Super Admin)</b></span>
                                @else
                                    <span class="d-lg-inline-flex me-lg-2"><b>(Admin)</b></span>
                                @endif
                            @endcan
                            <img class="rounded-circle" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" style="width: 40px; height: 40px;">
                            <!--<img style="margin-left: 10px;" class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />-->                            
                        </a>

                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <!--<p>{{ auth()->user()->email }}</p>-->
                            <div class="block px-4 py-2 text-xs text-gray-400" style="font-size: 12px; text-align: center;">
                                Administrar cuenta
                            </div>
                            <a href="/dashboard" class="dropdown-item">Mi Perfil</a>
                            <!--<a href="#" class="dropdown-item">Settings</a>-->
                            <!--<a href="#" class="dropdown-item">Log Out</a> -->
                            
                            <!--<div class="border-t border-gray-200 dark:border-gray-600"></div>-->

                            <!-- Authentication -->
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                            </form>

                            <a class="dropdown-item" onclick="document.getElementById('logout-form').submit();" style="cursor: pointer;">
                                {{ __('Cerrar sesión') }}
                            </a>                       
                        </div>
                    </div>