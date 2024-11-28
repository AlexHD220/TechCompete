<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetenciaCategoria extends Model
{
    use HasFactory;

    use SoftDeletes; // Habilita Soft Deletes

    public $timestamps = false; //--> ignorar una valor de la tabla


    public function users() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(User::class);
    }

    public function competencia() // --> Relacion Uno a x
    {
        return $this->belongsTo(Competencia::class);
    }

    public function categoria() // --> Relacion Uno a x
    {
        return $this->belongsTo(Categoria::class);
    }

    public function asesores() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(Asesor::class);
    }

    public function equipos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Equipo::class);
    }

    public function proyectos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Proyecto::class);
    }

    public function participantes() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Participante::class);
    }

    public function jueces() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(Juez::class);
    }

    public function horario() // --> Relacion Uno a Uno
    {
        return $this->hasOne(Horario::class);
    }

    public function circuitos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Circuito::class);
    }

    public function enfrentamientos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Enfrentamiento::class);
    }
    
    public function evaluaciones() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Evaluacion::class);
    }
}
