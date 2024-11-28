<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;
    
    public $timestamps = false; //--> ignorar una valor de la tabla

    protected $fillable = ['user_id','nombre', 'descripcion','asesor_id','competencia_id']; // <-- columnas llenables por el usuario (fillable) opuesto es guarded ES MEJOR ESTE

    public function competencia() // --> Relacion Uno a x
    {
        return $this->belongsTo(Competencia::class);
    }

    public function user() // --> Relacion Uno a Muchos (pertenece a)(relacion inversa)(Modelo que posee la columna foranea)
    {
        return $this->belongsTo(User::class);
    }

    public function institucion() // --> Relacion Uno a x
    {
        return $this->belongsTo(Institucion::class);
    }

    public function asesor() // --> Relacion Uno a x
    {
        return $this->belongsTo(Asesor::class);
    }

    public function competenciacategoria() // --> Relacion Uno a x
    {
        return $this->belongsTo(CompetenciaCategoria::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($proyecto) {
            // Elimina manualmente los participantes relacionados
            $proyecto->participantes()->delete();
        });
    }

    // Relación polimórfica inversa
    public function participantes()
    {
        return $this->morphMany(Participante::class, 'participable');
    }

    /*public function participantes() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Participante::class);
    }*/

    public function evaluaciones() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Evaluacion::class);
    }


    /*public function user()
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
    }*/
}
