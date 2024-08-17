<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asesor extends Model
{
    protected $table = 'asesores'; //<-- Cambiar el nombre de mi tabla
    
    use HasFactory;
    ///APLICAR MIGRACION ANTES DE DESCOMENTAR
    //use SoftDeletes; // Borrado logico a nivel de base de datos (levnatr bandera de eliminado logico)

    public $timestamps = false; //--> ignorar una valor de la tabla 
    protected $fillable = ['user_id','nombre','correo','telefono','escuela'/*,'usuario'*/]; // <-- columnas llenables por el usuario (fillable) opuesto es guarded ES MEJOR ESTE
    //protected $guarded = ['id']; // <-- columnas protegidas no llenables por el usuario (guarded)

    // Definición de la relación con Usuario 
    public function competencias()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->hasMany(Competencia::class);
    }


    public function user()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(User::class);
    }

    public function organizaciones(){
        return $this -> belongsToMany(Organizacion::class); //Pertenece a muchos
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

}
