<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Competencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['show']); //excepto estas necesitan iniciar sesion 
        
        $this->middleware('can:only-admin')->except('show');
        //return redirect()->route('competencia.index');
        
        //$this->middleware('can:only-admin')->except('index');
        //return redirect()->route('competencia.index');

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all();

        return view('categoria/indexCategoria', compact('categorias'));
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
        $request->validate([
            'nombre' => ['required', 'string', 'min:5', 'max:50', 'unique:categorias'],
            'descripcion' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        Categoria::create($request->all()); // <-- hace todo lo que esta abajo desde new hasta save
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
            'nombre' => ['required', 'string', 'min:5', 'max:50', Rule::unique('categorias')->ignore($categoria)],
            'descripcion' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        Categoria::where('id', $categoria->id)->update($request->except('_token','_method')); //opuesto de except (only)

        //return redirect() -> route('categoria.show', $categoria); //esto corresponde a el listado de route:list 
        //return redirect() -> route('categoria.index'); //esto corresponde a el listado de route:list 

        //return redirect() -> route('categoria.show', $categoria);
        return redirect() -> route('categoria.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        return redirect('/');
    }
}
