<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Administrador extends Model
{
    protected $table = 'users'; //<-- Cambiar el nombre de mi tabla

    use HasFactory;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    public $timestamps = false; //--> ignorar una valor de la tabla 

    protected $fillable = [
        'name', 'email', 'password', 'rol'
    ];



    public function user()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(User::class);
    }
}
