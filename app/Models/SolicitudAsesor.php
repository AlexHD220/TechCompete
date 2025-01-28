<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAsesor extends Model
{
    protected $table = 'solicitud_asesores'; //<-- Cambiar el nombre de mi tabla
    
    use HasFactory;        

    public $timestamps = false; //--> ignorar una valor de la tabla 
}
