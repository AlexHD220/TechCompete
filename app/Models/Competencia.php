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

    
    public function users() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(User::class, 'acceso_competencias', 'competencia_id', 'user_id');
    }

    public function instituciones() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(Institucion::class);
    }

    public function categorias() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(Categoria::class);
    }

    public function competencia_categorias() // --> Relacion Muchos a 1
    {
        return $this->hasMany (CompetenciaCategoria::class);
    }

    public function competencia_subcategorias() // --> Relacion Muchos a 1
    {
        return $this->hasMany (CompetenciaSubcategoria::class);
    }

    public function equipos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Equipo::class);
    }

    public function proyectos() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Proyecto::class);
    }

    public function jueces() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(Juez::class);
    }

    public function horarios() // --> Relacion Muchos a 1
    {
        return $this->hasMany (Horario::class);
    }

    public function acceso_competencias() // --> Relacion Muchos a Uno (Tiene muchos)
    {
        return $this->hasMany (AccesoCompetencia::class);
    }
    
    
    /*// Definición de la relación con Usuario (PENDIENTE DE HACERLO FUNCIONAR) NO FUNCIONO LO ELIMINE
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
    }*/
}
