<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategoria extends Model
{
    use HasFactory;

    //use SoftDeletes; // Habilita Soft Deletes

    public $timestamps = false; //--> ignorar un valor de la tabla

    protected $fillable = ['name','descripcion']; // <-- columnas llenables por el usuario (fillable) opuesto es guarded ES MEJOR ESTE

    public function categoria() // --> Relacion Uno a x
    {
        return $this->belongsTo(Categoria::class);
    }

}
