<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;
    public $timestamps = false; //--> ignorar una valor de la tabla 

    protected $fillable = ['user_id','nombre','asesor_id','competencia_id', 'categoria_id']; // <-- columnas llenables por el usuario (fillable) opuesto es guarded ES MEJOR ESTE

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

    public function competencia_categoria() // --> Relacion Uno a x
    {
        return $this->belongsTo(CompetenciaCategoria::class);
    }

    public function competencia_subcategoria() // --> Relacion Uno a x
    {
        return $this->belongsTo(CompetenciaSubcategoria::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($equipo) {
            // Elimina manualmente los participantes relacionados
            $equipo->participantes()->delete();
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

    public function robot() // --> Relacion Uno a x
    {
        return $this->hasOne(Robot::class);
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

    // Definición de la relación con Usuario 
    public function categoria()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(Categoria::class);
    }*/
}
