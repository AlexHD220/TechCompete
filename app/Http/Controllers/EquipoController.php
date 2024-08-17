<?php

namespace App\Http\Controllers;

use App\Mail\NotificaEquipoRegistrado;
use App\Models\Asesor;
use App\Models\Categoria;
use App\Models\Competencia;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class EquipoController extends Controller
{

    public function __construct()
    {
        //$this->middleware('can:only-user')->except('index', 'show');
        $this->middleware('can:only-user')->except('show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Uso de gate
        /*if (Gate::allows('only-user')) {
            $equipos = Equipo::where('user_id',Auth::id())->get(); //registros que solo pertenezcan al usuario logueado
        }
        else{
            $equipos = Equipo::all();
        }*/

        //$equipos = Equipo::where('user_id',Auth::id())->get(); //registros que solo pertenezcan al usuario logueado

        $asesores = Asesor::where('user_id',Auth::id())->get(); //registros que solo pertenezcan al usuario logueado

        $competencias = Competencia::where('tipo','Equipo')->get();

        // Eager Loading
        $equipos = Equipo::with('user')->where('user_id',Auth::id())
        ->with('competencia')
        ->get();

        return view('equipo/indexEquipo', compact('equipos','asesores','competencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $asesores = Asesor::where('user_id',Auth::id())->get(); //registros que solo pertenezcan al usuario logueado

        $competencias = Competencia::where('tipo','Equipo')->get();

        //$categorias = Categoria::all();

        $categorias = Categoria::whereHas('competencias', function ($query) {
            $query->where('tipo', 'Equipo');
        })->get();

        return view('equipo/createEquipo', compact('asesores','competencias', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            'nombre' => ['required', 'string', 'min:4', 'max:20', 'unique:equipos'],
            'asesor_id' => ['required', 'not_in: Selecciona una opción'],
            'competencia_id' => ['required', 'not_in: Selecciona una opción'],
            'categoria_id' => ['required', 'not_in: Selecciona una opción'],
        ]);

        $request->merge(['user_id' => Auth::id()]); //Inyectar el user id en el request

        $equipo = Equipo::create($request->all());

        // Correo elecronico
        
        $competencia = Competencia::where('id',$equipo->competencia_id)->first();

        $categoria = Categoria::where('id',$equipo->categoria_id)->first();

        //$categoria = Categoria::where('id',$equipo->categoria_id)->first();

        Mail::to($request->user())->send(new NotificaEquipoRegistrado($equipo, $competencia, $categoria));

        return redirect('/equipo'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipo $equipo)
    {

        // Solo administradores
        if (!Gate::allows('only-admin')) {
            
            if (!Gate::allows('gate-equipo', $equipo)) { // Uso de gate
                return redirect('/asesor');
            }
        } 
        
        /*$asesor = Asesor::where('id',$equipo->asesor_id)->first(); //registro que solo pertenezcan al usuario logueado (1 solo arreglo)

        $competencia = Competencia::where('id',$equipo->competencia_id)->first();

        $categoria = Categoria::where('id',$equipo->categoria_id)->first();*/

        //dd($asesor);

        //return view('equipo/showEquipo',compact('equipo', 'asesor', 'competencia','categoria')); //asesor es el usuario actual a mostrar

        return view('equipo/showEquipo',compact('equipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipo $equipo)
    {
        // Uso de gate
        if (!Gate::allows('gate-equipo', $equipo)) {
            return redirect('/equipo');
        }

        $asesores = Asesor::where('user_id',Auth::id())->get(); //registros que solo pertenezcan al usuario logueado

        $competencias = Competencia::where('tipo','Equipo')->get();

        $categorias = Categoria::whereHas('competencias', function ($query) {
            $query->where('tipo', 'Equipo');
        })->get();
        
        return view('equipo/editEquipo',compact('equipo', 'asesores', 'competencias','categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipo $equipo)
    {
        // Uso de gate
        if (!Gate::allows('gate-equipo', $equipo)) {
            return redirect('/equipo');
        }

        $request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            'nombre' => ['required', 'string', 'min:4', 'max:20', Rule::unique('equipos')->ignore($equipo)],
            'asesor_id' => ['required'],
            'competencia_id' => ['required'],
            'categoria_id' => ['required'],
        ]);

        Equipo::where('id', $equipo->id)->update($request->except('_token','_method')); //opuesto de except (only)

        //return redirect() -> route('categoria.show', $categoria); //esto corresponde a el listado de route:list 
        //return redirect() -> route('equipo.index'); //esto corresponde a el listado de route:list 

        return redirect() -> route('equipo.show', $equipo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        // Uso de gate
        if (!Gate::allows('gate-equipo', $equipo)) {
            return redirect('/equipo');
        }

        $equipo -> delete();
        return redirect('/equipo');
    }
}
