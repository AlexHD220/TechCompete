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
        Gate::define('only-admin', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 1;
        });

        Gate::define('only-user', function (User $user) { // Gate para limitar el acceso de un usuario a ciertos metodos o peticiones
            return $user->rol == 2;
        });
    }
}
