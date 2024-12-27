<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Administrador;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //ID Usuario
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

use Illuminate\Support\Str;

class StaffController extends Controller
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
        $superstaffs = User::where('rol',3)->get();
        $staffs = User::where('rol',4)->get();

        $disabledsuperstaffs = User::onlyTrashed()->where('rol',3)->get();
        $disabledstaffs = User::onlyTrashed()->where('rol',4)->get();

        return view("staff/indexStaff",compact('superstaffs', 'staffs','disabledsuperstaffs','disabledstaffs')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff/createStaff');
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
            'password' => Hash::make(Str::random(10)),
        ]);
        
        //$this->createTeam($user);
        


            /*, function (User $user) {
                $this->createTeam($user);
            };*/

        $user->sendEmailVerificationNotification();
        
        return redirect('/staff'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(User $staff)
    {
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $staff)
    {
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $staff)
    {
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $staff)
    {
        //dd($staff);
        $staff->delete();

        return redirect('/staff');
    }

    public function hardDestroy(User $staff) 
    {
        //dd($staff);
        $staff->forceDelete();

        return redirect('/staff');
    }

    public function trashed()
    {
        // Obtiene todos los registros eliminados
        $superstaffs = User::onlyTrashed()->where('rol',3)->get();
        $staffs = User::onlyTrashed()->where('rol',4)->get();

        //dd($superstaffs);

        // Retorna la vista con los registros eliminados
        return view("staff/trashedStaff",compact('superstaffs', 'staffs')); 
    }

    public function restore($id)
    {

        //dd($id);

        // Busca el registro eliminado por ID
        $staff = User::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados
    
        // Restaura el registro
        $staff->restore();
        

        return redirect('/staff/trashed');
    }

    public function disabledharddestroy($id) 
    {
        //dd($id);

        // Busca el registro eliminado por ID
        $staff = User::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados

        //dd($staff);
        $staff->forceDelete();

        return redirect('/staff/trashed');
    }

    public function makeUpper(User $staff) 
    {

        $staff->rol = 3; // Cambia el rol a 3
        $staff->save();

        return redirect('/staff');
    }

    public function makeLower(User $staff) 
    {

        $staff->rol = 4; // Cambia el rol a 4
        $staff->save();

        /*return redirect('/staff')->with('notificacion', [
            'titulo' => 'Registro exitoso',
            'mensaje' => 'Tu cuenta se ha creado correctamente.',
        ]);*/
        

        return redirect('/staff');
    }
}
