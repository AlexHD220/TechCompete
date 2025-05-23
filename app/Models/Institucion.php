<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Institucion extends Model
{
    protected $table = 'instituciones'; //<-- Cambiar el nombre de mi tabla

    use HasFactory;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    use SoftDeletes; // Habilita Soft Deletes

    public $timestamps = false; //--> ignorar una valor de la tabla


    public function user() // --> Relacion Uno a x
    {
        return $this->belongsTo(User::class);
    }

    public function competencias() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(Competencia::class);
    }

    public function asesores() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Asesor::class);
    }

    public function asesor_institucion_solicitudes() // --> Relacion Muchos a 1
    {
        return $this->hasMany (AsesorInstitucionSolicitud::class);
    }

    public function equipos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Equipo::class);
    }

    public function proyectos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Proyecto::class);
    }
}
