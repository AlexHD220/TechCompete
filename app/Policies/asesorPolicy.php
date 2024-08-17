<?php

namespace App\Policies;

use App\Models\Asesor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class asesorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Asesor $asesor): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Asesor $asesor): bool //--> :bol especifica que el tipo de dato que regresara la funcion es un booleano
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    // DELETE solo es el nombre de la funcion y uede aplicarse a otro que no sea solo DELETE, puede aplicarse en otros
    public function delete(User $user, Asesor $asesor): bool
    {
        //return false; // Aplicar la logica que necesitemos (esta logica es que no me va a permitir eliminar ninguno asesor)
        //Aqui regreso falso siempre y no le permito a nadie
        
        // Solo los usuarios con id 2 podran entrar a esta parte
        return $user->id == '1';

        //Ejemplo
        //return $color->productos()->count() > 0 ? false: true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Asesor $asesor): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Asesor $asesor): bool
    {
        //
    }
}
