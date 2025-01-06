<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Categoria;
use App\Models\Competencia;
use App\Models\Equipo;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompetenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct() //proteger con inicio de sesion aquellas pestañas que yo quiera
    {        
        $this->middleware('auth')->except(['index','show']); //excepto estas necesitan iniciar sesion 

        $this->middleware('can:only-superadmin')->except('index', 'show');
    }
     
    //otra variante es "only" para autenticar solo aquellas que notros queramos 
    

    public function index()
    {
        $competencias = Competencia::all();

        $categorias = Categoria::all();
        
        // Eager Loading
        //$competencias = Competencia::with('categorias')->get(); // Hace una consulta más

        return view('competencia/indexCompetencia', compact('competencias','categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$asesores = Asesor::all();

        //$categorias = Categoria::all();
        
        //$asesores = Asesor::where('user_id',Auth::id())->get(); //registros que solo pertenezcan al usuario logueado

        //return view('competencia/createCompetencia', compact('asesores','categorias'));
        //return view('competencia/createCompetencia', compact('categorias'));

        return view('competencia/createCompetencia');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            //'identificador' => ['required', 'string', 'min:5', 'max:50', 'unique:competencias'],
            'fecha' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addYears(2)->format('Y-m-d')],
            'duracion' => ['required','integer','min:1','max:100'],
            //'asesor_id' => ['required', 'not_in:Selecciona una opción'],
            'tipo' => ['required', 'not_in:-'],
            //'categoria_id' => ['required'],
            'imagen' => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:15360'], // Máximo 15 Mb
        ]);

        /*$competencia = new Competencia();

        $competencia -> asesor_id = $request -> asesor_id;
        $competencia -> identificador = $request -> identificador;
        $competencia -> fecha = $request -> fecha;
        $competencia -> duracion = $request -> duracion;

        $competencia->save();*/

        // Validar que el archivo se haya cargado bien
        /*if ($request->file('imagen')->isValid()) {
            // Instancia del archivo
            $request->file('imagen')->store('imagenes competencias'); // 'imaganes competencias' --> carpeta 
        }*/

        /*$request -> merge([
            'nombre_original_imagen' =>  $request->file('imagen')->getClientOriginalName(),
            //'ubicacion_imagen' =>  $request->file('imagen')->store('imagenes_competencias'),
            'ubicacion_imagen' =>  $request->file('imagen')->storeAs('public/imagenes_competencias', 'Logo_'.$request->identificador.'.'. $request->file('imagen')->extension()),
        ]);*/

        //$competencia = Competencia::create($request->all()); // <-- hace todo lo que esta abajo desde new hasta save
        
        // Insertar en la tabla pivote relacion m:n
        //$competencia->categorias()->attach($request->categoria_id); //detach() elimina de la lista el usuario que le pasemos 
        
        
        //dd($registrojuez);

        // Generar el enlace de Google Maps
        $googleMapsLink = "https://www.google.com/maps?q={$request->latitude},{$request->longitude}";
        
        $competencia = new Competencia();

        // Crear el registro en la base de datos
        
        $competencia->name = $request->name;
        $competencia->descripcion = $request->descripcion;
        $competencia->fecha = $request->fecha;
        $competencia->duracion = $request->duracion;
        $competencia->tipo = $request->tipo;
        $competencia->sede = $request->sede;
        $competencia->latitud = $request->latitude;
        $competencia->longitud = $request->longitude;
        $competencia->mapa_link = $googleMapsLink;
        $competencia->ubicacion_imagen = $request->file('imagen')->storeAs('public/imagenes_competencias', 'Portada_'.$request->name.'.'. $request->file('imagen')->extension());


        //dd($registrojuez->id);
        
        $competencia->save();

        return redirect()->route('competencia.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Competencia $competencia)
    {
        //$competencias = Competencia::all();

        if (Gate::allows('only-superadmin')) {
            $equipos = Equipo::where('competencia_id',$competencia->id)->get(); 
            $proyectos = Proyecto::where('competencia_id',$competencia->id)->get(); 
            
            return view('competencia/showCompetencia',compact('competencia','equipos','proyectos')); // Listar los proyectos y equipos registrados en esa competencia
        }
        else{
            return view('competencia/showCompetencia',compact('competencia'));
        }

        //$asesor = Asesor::where('id',$equipo->asesor_id)->first();

        //return view('competencia/showCompetencia',compact('competencia')); //asesor es el usuario actual a mostrar
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competencia $competencia)
    {
        $categorias = Categoria::all();

        return view('competencia/editcompetencia',compact('competencia', 'categorias')); //formulario para editar la base, asesor es el usuario a editar
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Competencia $competencia)
    {
        //dd($request);

        $request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            'identificador' => ['required', 'string', 'min:5', 'max:50', Rule::unique('competencias')->ignore($competencia)],
            'fecha' => ['required', 'date', 'before_or_equal:' . now()->addYears(2)->format('Y-m-d')],
            'duracion' => ['required','integer','min:1','max:100'],
            //'asesor_id' => ['required', 'not_in:Selecciona una opción'],
            'tipo' => ['required'],
            'categoria_id' => ['required'],
            'imagen' => ['file', 'mimes:png,jpg,jpeg', 'max:5120'], // Máximo 5 Mb
        ]);

        if ($request->hasFile('imagen')) {
            //dd($request);
            $request -> merge([
                'nombre_original_imagen' =>  $request->file('imagen')->getClientOriginalName(),
                //'ubicacion_imagen' =>  $request->file('imagen')->store('imagenes_competencias'),
                'ubicacion_imagen' =>  $request->file('imagen')->storeAs('public/imagenes_competencias', 'Logo_'.$request->identificador.'.'. $request->file('imagen')->extension()),
            ]);
        } 

        Competencia::where('id', $competencia->id)->update($request->except('_token','_method','categoria_id','imagen'));

        // Actualizar tabla pivote con los nuevos registros  
        $competencia->categorias()->sync($request->input('categoria_id'));

        // Insertar en la tabla pivote relacion m:n --> PENDIENTE FINAL
        //$competencia->categorias()->attach($request->categoria_id); //detach() elimina de la lista el usuario que le pasemos


        //Competencia::where('id', $competencia->id)->update($request->except('_token','_method')); //opuesto de except (only)

        //return redirect() -> route('categoria.show', $categoria); //esto corresponde a el listado de route:list 
        
        //return redirect() -> route('competencia.index'); //esto corresponde a el listado de route:list 

        return redirect() -> route('competencia.show', $competencia);
    }

    public function drafts()
    {
        // Obtiene todos los registros eliminados
        
        $jueces = Competencia::onlyTrashed()->get();

        //dd($jueces);

        // Retorna la vista con los registros eliminados
        return view("juez/trashedJuez",compact('jueces')); 
    }
    
    public function previous()
    {
        // Obtiene todos los registros eliminados
        
        $jueces = Competencia::onlyTrashed()->get();

        //dd($jueces);

        // Retorna la vista con los registros eliminados
        return view("juez/trashedJuez",compact('jueces')); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competencia $competencia)
    {
        //dd($competencia);
        
        //$competencia->categorias()->detach(); // Eliminar registros de tabla pivote

        // Soft delete tabla pivote
        
        
        //$competencia->categorias()->update(['deleted_at' => now()]);        

        //dd($juez);

        
        //$juez->user->delete();  //Relacion 1:1

        $competencia -> delete();

        return redirect('/competencia');
    }

    public function harddestroy(Competencia $juez) 
    {
        //dd($juez->registro_juez);

        $juez->forceDelete();
        
        $juez->user->forceDelete();
        
        $juez->registro_juez->delete();

        return redirect('/competencia');
    }

    public function trashed()
    {
        // Obtiene todos los registros eliminados
        
        $jueces = Competencia::onlyTrashed()->get();

        //dd($jueces);

        // Retorna la vista con los registros eliminados
        return view("juez/trashedJuez",compact('jueces')); 
    }

    /**
     * Display the specified delete resource.
     */
    public function showtrashed($id)
    {
        //
    }

    public function restore($id)
    {

        //dd($id);

        // Busca el registro eliminado por ID
        $juez = Competencia::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados
    

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
        $juez = Competencia::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados

        //dd($juez->user()->withTrashed()->first());

        //dd($juez->registro_juez);

        $juez->forceDelete();

        $juez->user()->withTrashed()->forceDelete(); // Eliminar el usuario relacionado

        $juez->registro_juez->delete();

        return redirect('/juez/trashed');
    }

}
