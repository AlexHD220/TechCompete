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

    public function competencia_categorias() // --> Relacion Muchos a Muchos
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

    // En el modelo Juez
    public function registro_juez()
    {
        return $this->belongsTo(RegistroJuez::class);  // Asegúrate de que el nombre del método es correcto
    }

    public function jueces_competencias() // --> Relacion Muchos a Uno (Tiene muchos)
    {
        return $this->hasMany (JuecesCompetencia::class);
    }
}
