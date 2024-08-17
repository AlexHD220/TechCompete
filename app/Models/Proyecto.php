<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['user_id','nombre', 'descripcion','asesor_id','competencia_id']; // <-- columnas llenables por el usuario (fillable) opuesto es guarded ES MEJOR ESTE


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

    public function categorias(){
        return $this -> belongsToMany(Categoria::class); //Pertenece a muchos
    }
}
