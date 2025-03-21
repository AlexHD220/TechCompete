<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory;

    use SoftDeletes; // Habilita Soft Deletes

    public $timestamps = false; //--> ignorar una valor de la tabla

    protected $fillable = ['name', 'descripcion']; // <-- columnas llenables por el usuario (fillable) opuesto es guarded ES MEJOR ESTE

    public function competencias() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(Competencia::class);
    }

    public function competencia_categorias() // --> Relacion Muchos a 1
    {
        return $this->hasMany (CompetenciaCategoria::class);
    }

    public function competencia_subcategorias() // --> Relacion Muchos a 1
    {
        return $this->hasMany (CompetenciaSubcategoria::class);
    }

    public function subcategorias() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Subcategoria::class);
    }


    /*public function competencias(){
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
    }*/
}
