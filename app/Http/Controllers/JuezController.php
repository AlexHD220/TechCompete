<?php

namespace App\Http\Controllers;

use App\Models\Juez;
use App\Models\Administrador;
use App\Models\RegistroJuez;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //ID Usuario
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

use Illuminate\Support\Str;


class JuezController extends Controller
{

    protected $model = User::class;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$jueces = Juez::all();

        $jueces = Juez::orderBy('name', 'asc')->get();
        
        $disabledjueces = Juez::onlyTrashed()->get();

        $disabledjuecescount = $disabledjueces->count();

        return view("juez/indexJuez",compact('jueces', 'disabledjuecescount')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createjuez()
    {
        return redirect('/');
    }

    public function create($codigo)
    {
        // Verificar si el código existe y es válido
        $registro = RegistroJuez::where('codigo', $codigo)->first();

        if (!$registro || $registro->expiracion_date < now() || $registro->validado) { // Pruebas Pendientes con !$registro->validacion_date
            return redirect('/');
        }

        // Retornar la vista del formulario con el código
        return view('juez/createjuez', compact('codigo'));        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los campos
        $request->validate([            
            'codigo' => 'required|exists:registro_jueces,codigo',
            'email' => 'required|email|',
            'telefono' => ['nullable','numeric','unique:users,telefono',],
            //'codigo_registro' => 'required|in:' . $request->codigo, // Verifica si el código ingresado es el mismo que el de la ruta
        ]);

        // Buscar el código de registro en la base de datos
        $codigo = RegistroJuez::where('codigo', $request->codigo)->first();

        // Verificar que el código no haya expirado
        /*if ($codigo->expiracion_date < now()) {
            return redirect()->back()->withErrors(['codigo' => 'El código de registro ha expirado.'])->withInput();
        }*/

        // Verificar que el código no haya sido validado
        if ($codigo->validado) {
            return redirect()->back()->withErrors(['codigo' => 'El código ya fue validado.'])->withInput();
        }

        if ($request->codigo_registro !== $request->codigo) {
            return redirect()->back()->withErrors(['codigo' => 'Parece que este código de registro no te pertenece.'])->withInput();
        }

        // Verificar que el correo ingresado coincida con el correo vinculado al código
        if ($codigo->email !== $request->email) {
            return redirect()->back()->withErrors(['email' => 'Este correo electrónico no está vinculado con el código de registro.'])->withInput();
        }


        $user = User::create([
            'rol' => 7,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,   
            'telefono' => $request->telefono,             
            'password' => Hash::make($request->password),                
        ]);

        $juez = new Juez(); //quiero una nueva instancia de este modelo que va a representar mi tabla (representante de alto nivel)
        $juez->registro_juez_id = $codigo->id;
        $juez->user_id = $user->id;        
        $juez->name = $request->name;
        $juez->lastname = $request->lastname; //asignar atributos que corresonden por como se llaman mis columnas
        $juez->email = $request->email;      
        $juez->telefono = $request->telefono;
        $juez->save();

        $codigo->validado = true;  // Actualiza el estado de validación
        $codigo->validacion_date = now();  // Establece la fecha de validación
        $codigo->expiracion_date = null;  // Elimina la fecha de expiración
        $codigo->save();  // Guarda la actualización

        // Enviar automáticamente el correo de verificación
        //event(new Registered($user));
        $user->sendEmailVerificationNotification();

        // Redirigir con un mensaje de éxito
        return redirect()->route('login');
    }

    /**
     * Display the specified resource.
     */
    public function show(Juez $juez)
    {
        // Solo administradores
        /*if (!Gate::allows('only-admin')) {
            
            if (!Gate::allows('gate-asesor', $juez)) { // Uso de gate
                return redirect('/asesor');
            }
        }*/     

        return view('juez/showJuez',compact('juez')); //asesor es el usuario actual a mostrar
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Juez $juez)
    {
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Juez $juez)
    {
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Juez $juez)
    {
        //dd($juez);

        $juez->delete();
        
        $juez->user->delete();        

        return redirect('/juez');
    }

    public function harddestroy(Juez $juez) 
    {
        //dd($juez->registro_juez);

        $juez->forceDelete();
        
        $juez->user->forceDelete();
        
        $juez->registro_juez->delete();

        return redirect('/juez');
    }

    public function trashed()
    {
        // Obtiene todos los registros eliminados
        
        $jueces = Juez::onlyTrashed()->orderBy('name', 'asc')->get();

        //dd($jueces);

        // Retorna la vista con los registros eliminados
        return view("juez/trashedJuez",compact('jueces')); 
    }

    public function restore($id)
    {

        //dd($id);

        // Busca el registro eliminado por ID
        $juez = Juez::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados
    

        //dd($juez->user()->withTrashed()->first());

        // Restaura el registro
        $juez->restore();
        
        $juez->user()->withTrashed()->restore(); // Restaurar el usuario relacionado    

        return redirect('/juez/trashed');
    }

    public function disabledharddestroy($id) 
    {
        //dd($id);

        // Busca el registro eliminado por ID
        $juez = Juez::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados

        //dd($juez->user()->withTrashed()->first());

        //dd($juez->registro_juez);

        $juez->forceDelete();

        $juez->user()->withTrashed()->forceDelete(); // Eliminar el usuario relacionado

        $juez->registro_juez->delete();

        return redirect('/juez/trashed');
    }

}
