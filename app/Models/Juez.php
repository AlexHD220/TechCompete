<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Juez extends Model
{
    protected $table = 'jueces'; //<-- Cambiar el nombre de mi tabla

    use HasFactory;

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

    public function competenciacategorias() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(CompetenciaCategoria::class);
    }
    
    public function circuitos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Circuito::class);
    }

    public function evaluaciones() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Evaluacion::class);
    }

    public function registrojueces() // --> Relacion Uno a Uno (Pertenece a)(Relacion inversa)(Modelo que posee la columna foranea)
    {
        return $this->belongsTo(RegistroJuez::class);
    }
}
