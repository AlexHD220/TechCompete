<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    use HasFactory;
    protected $table = 'organizaciones';
    public $timestamps = false;
    
    public function asesores(){
        return $this -> belongsToMany(Asesor::class); //Pertenece a muchos
    }
}
