<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competencia extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false; //--> ignorar una valor de la tabla 

    protected $fillable = ['identificador','fecha', 'duracion', 'tipo','asesor_id', 'ubicacion_imagen', 'nombre_original_imagen']; // <-- columnas llenables por el usuario (fillable) opuesto es guarded ES MEJOR ESTE

    // Definición de la relación con Usuario (PENDIENTE DE HACERLO FUNCIONAR) NO FUNCIONO LO ELIMINE
    public function asesor()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(Asesor::class);
    }


    // Definición de la relación con Usuario 
    public function equipos()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->hasMany(Equipo::class);
    }

    // Definición de la relación con Usuario 
    public function proyectos()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->hasMany(Proyecto::class);
    }


    public function categorias(){
        return $this -> belongsToMany(Categoria::class); //Pertenece a muchos
    }
}
