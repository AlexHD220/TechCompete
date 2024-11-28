<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    use HasFactory;

    public $timestamps = false; //--> ignorar una valor de la tabla

    // Relación polimórfica con 'equipo' o 'proyecto'
    public function participable()
    {
        return $this->morphTo(); // Esto maneja la relación polimórfica
    }

    /*public function equipo() // --> Relacion Uno a x
    {
        return $this->belongsTo(Equipo::class);
    }

    public function proyecto() // --> Relacion Uno a x
    {
        return $this->belongsTo(Proyecto::class);
    }*/
}
