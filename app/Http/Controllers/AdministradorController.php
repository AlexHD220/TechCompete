<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //ID Usuario
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class AdministradorController extends Controller
{
    protected $model = User::class;

    public function __construct()
    {
        $this->middleware('can:only-admin');

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $administradores = User::where('rol',1)->get();

        return view("administrador/indexAdmin",compact('administradores')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrador/createAdmin');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //$user = $this->create($request->all());
        //$request->merge(['tipo' => 1]); //Inyectar el user id en el request
        
        //Tabla pivote
        //$asesor = Asesor::create($request->only('id'));

        //dd($request->organizacion_id); //PRUEBA DD

        //User::create($request->all()); // <-- hace todo lo que esta abajo desde new hasta save
        
        $request->validate([
            'name' => ['required', 'string', 'min:10', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'email' => ['required', 'string', 'email', 'min:5', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:50', new Password, 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        $user = User::create([
                'rol' => 1,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),                
        ]);
        
        $this->createTeam($user);
        


            /*, function (User $user) {
                $this->createTeam($user);
            };*/

        $user->sendEmailVerificationNotification();
        
        return redirect('/administrador'); 
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $administrador) 
    {
        //dd($administrador);
        $administrador->delete();

        return redirect('/administrador');
    }
}
