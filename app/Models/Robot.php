<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    use HasFactory;

    public function equipo() // --> Relacion Uno a x
    {
        return $this->belongsTo(Equipo::class);
    }

    //PENDIENTE ENFRENTAMIENTO

    public function circuitos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Circuito::class);
    }
}
