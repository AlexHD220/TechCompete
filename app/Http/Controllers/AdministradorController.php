<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Team;
use App\Models\User;
use App\Rules\ValidateUniqueInTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //ID Usuario
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

use Illuminate\Support\Str;

class AdministradorController extends Controller
{
    protected $model = User::class;

    public function __construct()
    {
        $this->middleware('can:only-superadmin');

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $superadministradores = User::where('rol',1)->orderBy('name', 'asc')->get();
        $administradores = User::where('rol',2)->orderBy('name', 'asc')->get();

        $disabledsuperadministradores = User::onlyTrashed()->where('rol',1)->get();
        $disabledadministradores = User::onlyTrashed()->where('rol',2)->get();

        return view("administrador/indexAdmin",compact('superadministradores', 'administradores','disabledsuperadministradores','disabledadministradores')); 
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
        $request->validate([
            'email' => ['required', 'email', new ValidateUniqueInTables(['users', 'registro_jueces'])], //| unique:registro_jueces,email",
            'telefono' => ['nullable','numeric','unique:users,telefono',],
            // Otras reglas de validación para otros campos
        ]);

        //$user = $this->create($request->all());
        //$request->merge(['tipo' => 1]); //Inyectar el user id en el request
        
        //Tabla pivote
        //$asesor = Asesor::create($request->only('id'));

        //dd($request-> lastname); //PRUEBA DD para verificar los datos del formulario

        //User::create($request->all()); // <-- hace todo lo que esta abajo desde new hasta save
        
        /*$request->validate([
            'name' => ['required', 'string', 'min:10', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'email' => ['required', 'string', 'email', 'min:5', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:50', new Password, 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);*/

        $user = User::create([
                'rol' => $request->rol,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make(Str::random(10)),
        ]);
        
        //$this->createTeam($user);
        


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
    public function show(User $administrador)
    {
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $administrador)
    {
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $administrador)
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

    public function harddestroy(User $administrador) 
    {
        //dd($administrador);
        $administrador->forceDelete();

        return redirect('/administrador');
    }

    public function trashed()
    {
        // Obtiene todos los registros eliminados
        $superadministradores = User::onlyTrashed()->where('rol',1)->orderBy('name', 'asc')->get();
        $administradores = User::onlyTrashed()->where('rol',2)->orderBy('name', 'asc')->get();

        //dd($superadministradores);

        // Retorna la vista con los registros eliminados
        return view("administrador/trashedAdmin",compact('superadministradores', 'administradores')); 
    }

    public function restore($id)
    {

        //dd($id);

        // Busca el registro eliminado por ID
        $administrador = User::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados
    
        // Restaura el registro
        $administrador->restore();
        

        return redirect('/administrador/trashed');
    }

    public function disabledharddestroy($id) 
    {
        //dd($id);

        // Busca el registro eliminado por ID
        $administrador = User::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados

        //dd($administrador);
        $administrador->forceDelete();

        return redirect('/administrador/trashed');
    }

    public function makeUpper(User $administrador) 
    {

        $administrador->rol = 1; // Cambia el rol a 1
        $administrador->save();

        return redirect('/administrador');
    }

    public function makeLower(User $administrador) 
    {

        $administrador->rol = 2; // Cambia el rol a 2
        $administrador->save();

        /*return redirect('/administrador')->with('notificacion', [
            'titulo' => 'Registro exitoso',
            'mensaje' => 'Tu cuenta se ha creado correctamente.',
        ]);*/
        

        return redirect('/administrador');
    }
}
