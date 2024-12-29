<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JuecesCompetencia extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false; //--> ignorar una valor de la tabla 

    public function juez() // --> Relacion Uno a Muchos (pertenece a)(relacion inversa)(Modelo que posee la columna foranea)
    {
        return $this->belongsTo(Juez::class);
    }

    public function competencia_categoria() // --> Relacion Uno a Muchos (pertenece a)(relacion inversa)(Modelo que posee la columna foranea)
    {
        return $this->belongsTo(CompetenciaCategoria::class);
    }
}
