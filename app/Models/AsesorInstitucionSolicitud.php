<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsesorInstitucionSolicitud extends Model
{
    protected $table = 'asesor_institucion_solicitudes'; //<-- Cambiar el nombre de mi tabla
    
    use HasFactory;        

    public $timestamps = false; //--> ignorar una valor de la tabla 

    public function institucion() // --> Relacion Uno a x
    {
        return $this->belongsTo(institucion::class);
    }

    public function asesor() // --> Relacion Uno a x
    {
        return $this->belongsTo(asesor::class);
    }
}
