<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroJuez extends Model
{
    protected $table = 'registro_jueces'; //<-- Cambiar el nombre de mi tabla

    use HasFactory;

    //use SoftDeletes; // Habilita Soft Deletes

    public $timestamps = false; //--> ignorar una valor de la tabla
    
    public function juez() // --> Relacion Uno a Uno (Tiene uno)
    {
        return $this->hasOne(Juez::class);
    }

    public function user() // --> Relacion Uno a Muchos (pertenece a)(relacion inversa)(Modelo que posee la columna foranea)
    {
        return $this->belongsTo(User::class);
    }
}
