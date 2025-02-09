<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Asesor;
use App\Models\Equipo;
use App\Models\Proyecto;
use App\Models\User;
use App\Policies\asesorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Barryvdh\Debugbar\Facades\Debugbar;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Asesor::class => asesorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

        //Barra de debug desabhilitada en pagina principal
        Debugbar::disable();

        ///Evitar que un usuario acceda a un asesor que no le pertenece
        Gate::define('gate-asesor', function (User $user, Asesor $asesor) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->id === $asesor->user_id;
        });

        ///Evitar que un usuario acceda a un equipo que no le pertenece
        Gate::define('gate-equipo', function (User $user, Equipo $equipo) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->id === $equipo->user_id;
        });

        ///Evitar que un usuario acceda a un proyecto que no le pertenece
        Gate::define('gate-proyecto', function (User $user, Proyecto $proyecto) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->id === $proyecto->user_id;
        });

        ///Evitar que un usuario acceda a un proyecto que no le pertenece
        Gate::define('gate-participante', function () { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return false;
        });


        /// Limitar permisos de administrador
        Gate::define('only-superadmin', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 1;
        });

        Gate::define('only-admin', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 2;
        });


        /// Limitar permisos de staff

        Gate::define('only-staff', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 3;
        });

        Gate::define('only-staffjr', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 4;
        });


        
        /// Pendiente
        Gate::define('only-user', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 0;
        });


        /// Limitar permisos de usuario        

        Gate::define('have-perfil', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            if($user->rol == 5 || $user->rol == 6 || $user->rol == 7){
                return true;
            }else{
                return false;
            }
        });

        Gate::define('only-institucion', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 5;
        });

        Gate::define('only-asesor', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 6;
        });

        Gate::define('only-juez', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 7;
        });


        Gate::define('autenticado', function ($user = null) {
            return $user !== null; // Retorna true si hay un usuario autenticado
        });

        Gate::define('mail-verificado', function (User $user) { 
            return $user->hasVerifiedEmail();
        });
    }
}
