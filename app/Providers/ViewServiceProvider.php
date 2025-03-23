<?php

namespace App\Providers;

use App\Models\Asesor;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user = Auth::user();
            
            if ($user && in_array($user->rol, [1, 2])) {
                // Obtener los asesores no verificados
                $cuentasAsesores = Asesor::where('verificada', 0)->where('observaciones', 0)->get();
                $NumcuentasAsesoresPendientes = $cuentasAsesores->count();
                
                // Compartir las variables globalmente en la vista                
                $view->with('NumcuentasAsesoresPendientes', $NumcuentasAsesoresPendientes);
            }
        });
    }
}
