<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones'; //<-- Cambiar el nombre de mi tabla

    use HasFactory;

    public $timestamps = false; //--> ignorar una valor de la tabla

    public function proyecto() // --> Relacion Uno a x
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function juez() // --> Relacion Uno a x
    {
        return $this->belongsTo(Juez::class);
    }

    public function competenciacategoria() // --> Relacion Uno a x
    {
        return $this->belongsTo(CompetenciaCategoria::class);
    }
}
