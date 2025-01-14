<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Competencia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['show']); //excepto estas necesitan iniciar sesion 
        
        $this->middleware('can:only-superadmin')->except('show');
        //return redirect()->route('competencia.index');
        
        //$this->middleware('can:only-superadmin')->except('index');
        //return redirect()->route('competencia.index');

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {            
        //$categorias = Categoria::orderBy('tipo', 'asc')->orderBy('name', 'asc')->get();

        $categorias = Categoria::orderBy('name', 'asc')->get();
        
        $disabledcategorias = Categoria::onlyTrashed()->get();

        $disabledcategoriascount = $disabledcategorias->count();

        return view('categoria/indexCategoria', compact('categorias', 'disabledcategoriascount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoria/createCategoria');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());

        $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:50', 'unique:categorias'],
            'tipo' => ['required'],
            'descripcion' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        //dd($request->all());

        //Categoria::create($request->all()); // <-- hace todo lo que esta abajo desde new hasta save

        $categoria = new Categoria();

        // Crear el registro en la base de datos
        
        $categoria->name = $request->name;
        $categoria->tipo = $request->tipo;
        $categoria->descripcion = $request->descripcion; 
        
        $categoria->save();

        return redirect('/categoria'); 
    }

    /**
     * Display the specified resource.
     */
    
    public function show(Categoria $categoria)
    {

        return view('categoria/showCategoria',compact('categoria'));
        
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categoria/editcategoria',compact('categoria')); //formulario para editar la base, asesor es el usuario a editar
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:50', Rule::unique('categorias')->ignore($categoria)],
            'descripcion' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        Categoria::where('id', $categoria->id)->update($request->except('_token','_method')); //opuesto de except (only)    

        //return redirect() -> route('categoria.show', $categoria); //esto corresponde a el listado de route:list 
        //return redirect() -> route('categoria.index'); //esto corresponde a el listado de route:list 

        //return redirect() -> route('categoria.show', $categoria);
        return redirect() -> route('categoria.index');
    }


    //=======================================================================================================================>


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Categoria $categoria)
    {
        //dd($categoria);

        //dd($request->ruta);
        
        //$competencia->categorias()->detach(); // Eliminar registros de tabla pivote

        // Soft delete tabla pivote
        
        
        //$categoria->categorias()->update(['deleted_at' => now()]);        

        //dd($juez);

        
        //$juez->user->delete();  //Relacion 1:1                        

        $categoria -> delete();

        return redirect($request->ruta);
    } 
    
    public function trashed()
    {
        // Obtiene todos los registros eliminados

        //dd($jueces);

        // Proximas categoria
        $categorias = Categoria::onlyTrashed()->orderBy('name', 'asc')->get();         

        return view('categoria/trashedCategoria', compact('categorias'));
    }

    public function restore($id)
    {

        //dd($id);

        // Busca el registro eliminado por ID
        $categoria = Categoria::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados
    

        //dd($juez->user()->withTrashed()->first());

        // Restaura el registro
        $categoria->restore();         

        return redirect('/categoria/trashed');
    }

    /*public function harddestroy(Categoria $juez) 
    {
        //dd($categoria->registro_juez);

        $categoria->forceDelete();
        
        $categoria->user->forceDelete();
        
        $categoria->registro_juez->delete();

        return redirect('/categoria');
    }*/

    /*public function disabledharddestroy($id) 
    {
        //dd($id);

        // Busca el registro eliminado por ID
        $categoria = Categoria::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados

        //dd($categoria->user()->withTrashed()->first());

        //dd($categoria->registro_juez);

        $categoria->forceDelete();

        $categoria->user()->withTrashed()->forceDelete(); // Eliminar el usuario relacionado

        $categoria->registro_juez->delete();

        return redirect('/categoria/trashed');
    }*/
    
}
