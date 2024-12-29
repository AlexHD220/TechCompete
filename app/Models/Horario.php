<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    public $timestamps = false; //--> ignorar una valor de la tabla

    public function competencia() // --> Relacion Uno a x
    {
        return $this->belongsTo(Competencia::class);
    }

    public function competencia_categoria() // --> Relacion Uno a x
    {
        return $this->belongsTo(CompetenciaCategoria::class);
    }
}
