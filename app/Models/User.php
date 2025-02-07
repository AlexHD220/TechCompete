<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    public $timestamps = false; //--> ignorar una valor de la tabla

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'lastname', 'email', 'password', 'rol', 'telefono', // Solo estas columnas pueden ser agregadas por el usuario, el resto no se agrega y se ignora
        //'name', 'email', 'password', 'rol', 'roleable_id','roleable_type','email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function institucion() // --> Relacion Uno a Uno
    {
        return $this->hasOne(Institucion::class);
    }

    public function asesor() // --> Relacion Uno a Uno
    {
        return $this->hasOne(Asesor::class);
    }

    public function juez() // --> Relacion Uno a Uno
    {
        return $this->hasOne(Juez::class);
    }

    public function competencias() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(Competencia::class, 'acceso_competencias', 'user_id', 'competencia_id');
    }

    public function competencia_categorias() // --> Relacion Muchos a Muchos
    {
        return $this->belongsToMany(CompetenciaCategoria::class);
    }


    //Pago de proyectos y equipos

    public function equipos() // --> Relacion Muchos a Uno (Tiene muchos)
    {
        return $this->hasMany (Equipo::class);
    }

    public function proyectos() // --> Relacion Muchos a Uno (Tiene muchos)
    {
        return $this->hasMany (Proyecto::class);
    }

    public function registro_jueces() // --> Relacion Muchos a Uno (Tiene muchos)
    {
        return $this->hasMany (RegistroJuez::class);
    }

    public function acceso_competencias() // --> Relacion Muchos a Uno (Tiene muchos)
    {
        return $this->hasMany (AccesoCompetencia::class);
    }

    /*public function asesores(){
        $this->hasmany(Asesor::class);
    }

    public function equipos(){
        $this->hasmany(Equipo::class);
    }

    public function proyectos(){
        $this->hasmany(Proyecto::class);
    }

    public function administrador()
    {
        //return $this->belongsTo(Usuario::class);
        return $this->belongsTo(Administrador::class);
    }

    public function roleable()
    {
        return $this->morphTo();
    }*/

}
