<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circuito extends Model
{
    use HasFactory;

    public function juez() // --> Relacion Uno a x
    {
        return $this->belongsTo(Juez::class);
    }

    public function robot() // --> Relacion Uno a x
    {
        return $this->belongsTo(Robot::class);
    }

    public function competencia_categoria() // --> Relacion Uno a x
    {
        return $this->belongsTo(CompetenciaCategoria::class);
    }
}
