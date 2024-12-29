<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfrentamiento extends Model
{
    use HasFactory;

    public function competencia_categoria() // --> Relacion Uno a x
    {
        return $this->belongsTo(CompetenciaCategoria::class);
    }

    // PENDIENTE ROBOTS
}
