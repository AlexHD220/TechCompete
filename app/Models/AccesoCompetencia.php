<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccesoCompetencia extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false; //--> ignorar una valor de la tabla 

    public function user() // --> Relacion Uno a Muchos (pertenece a)(relacion inversa)(Modelo que posee la columna foranea)
    {
        return $this->belongsTo(User::class);
    }

    public function competencia() // --> Relacion Uno a Muchos (pertenece a)(relacion inversa)(Modelo que posee la columna foranea)
    {
        return $this->belongsTo(Competencia::class);
    }
}
