<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;
    public $timestamps = false; //--> ignorar una valor de la tabla 

    protected $fillable = ['user_id','nombre','asesor_id','competencia_id', 'categoria_id']; // <-- columnas llenables por el usuario (fillable) opuesto es guarded ES MEJOR ESTE


    public function user()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(User::class);
    }

    // Definición de la relación
    public function asesor()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(Asesor::class);
    }

    // Definición de la relación con Usuario 
    public function competencia()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(Competencia::class);
    }

    // Definición de la relación con Usuario 
    public function categoria()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(Categoria::class);
    }
}
