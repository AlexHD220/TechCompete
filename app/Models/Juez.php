<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juez extends Model
{
    protected $table = 'jueces'; //<-- Cambiar el nombre de mi tabla

    use HasFactory;
}
