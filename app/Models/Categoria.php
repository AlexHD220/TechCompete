<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['nombre','descripcion']; // <-- columnas llenables por el usuario (fillable) opuesto es guarded ES MEJOR ESTE

    public function competencias(){
        return $this -> belongsToMany(Competencia::class); //Pertenece a muchos
    }

    // Definición de la relación con Equipo 
    public function equipos()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->hasMany(Equipo::class);
    }
    
    public function proyectos(){
        return $this -> belongsToMany(Proyecto::class); //Pertenece a muchos
    }
}
