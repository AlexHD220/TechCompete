<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Competencia;
use App\Models\Equipo;
use App\Models\Institucion;
use Illuminate\Http\Request;

use App\Mail\NotificaEquipoRegistrado;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use App\Models\Administrador;
use App\Models\Team;
use Illuminate\Auth\Events\Registered;
use Laravel\Fortify\Rules\Password;

class InstitucionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $administradores = User::where('rol',5)->get();

        return view("institucion/indexInstitucion",compact('administradores')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('institucion/createInstitucion');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:registro_jueces,email',
            'email' => ['unique:users'],
            // Otras reglas de validación para otros campos
        ]);

        $user = User::create([
            'rol' => 5,
            'name' => $request->name,
            'email' => $request->email,                
            'password' => Hash::make($request->password),                
        ]);
        
        //$this->createTeam($user);

        //$user->sendEmailVerificationNotification();


        // EQUIVALENTE --> Asesor::create($request->all()); 

        $institucion = new Institucion(); //quiero una nueva instanciade este modelo que va a representar mi tabla (representante de alto nivel)
        $institucion->user_id = $user->id;
        $institucion->name = $request->name;
        $institucion->email = $request->email;
        $institucion->tipo = $request->tipo; //asignari atributos que corresonden por como se llaman mis columnas
        $institucion->pais = $request->pais;
        $institucion->region = $request->region;
        $institucion->pagina_web = $request->pagina;
        $institucion->telefono = $request->telefono;
        $institucion->whatsapp = $request->whatsapp;
        $institucion->save();
        
        // Enviar automáticamente el correo de verificación
        //event(new Registered($user));
        $user->sendEmailVerificationNotification();

        // Redirigir con un mensaje de éxito
        //return redirect()->route('login');
        return redirect()->route('login')->with('success', '<b style="color: #41ef1f;">Su cuenta fue creada correctamente.</b> <br> <i>Antes de continuar, por favor verifique su dirección de correo electrónico.</i>');
        
        //return redirect('/institucion'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Institucion $institucion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institucion $institucion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institucion $institucion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institucion $institucion)
    {
        //
    }
}
