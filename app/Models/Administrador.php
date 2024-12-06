<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Administrador extends Model
{
    //protected $table = 'users'; //<-- Cambiar el nombre de mi tabla

    protected $table = 'administradores'; //<-- Cambiar el nombre de mi tabla

    use HasFactory;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    public $timestamps = false; //--> ignorar una valor de la tabla 

    protected $fillable = [
        'name', 'lastname', 'email', 'password', 'rol' // Solo estas columnas pueden ser agregadas por el usuario, el resto no se agrega y se ignora
    ];



    public function user()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(User::class);
    }
}
