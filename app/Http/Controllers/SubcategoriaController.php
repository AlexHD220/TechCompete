<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubcategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$categorias = Categoria::orderBy('tipo', 'asc')->orderBy('name', 'asc')->get();

        $subcategorias = Subcategoria::orderBy('nivel', 'asc')->get();

        return view('subcategoria/indexSubcategoria', compact('subcategorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /*public function create()
    {
        //
    }*/

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nivel' => ['required', 'string', 'min:5', 'max:50', 'unique:subcategorias'],
        ]);

        $subcategoria = new Subcategoria();

        // Crear el registro en la base de datos
        
        $subcategoria->nivel = $request->nivel;
        
        $subcategoria->save();

        return redirect('/subcategoria'); 
    }

    /**
     * Display the specified resource.
     */
    /*public function show(Subcategoria $subcategoria)
    {
        //
    }*/

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategoria $subcategoria)
    {        
        $subcategorias = Subcategoria::orderBy('nivel', 'asc')->get();

        // Eliminar el registro de la lista en memoria
        $subcategorias = $subcategorias->reject(function ($item) use ($subcategoria) {
            return $item->id === $subcategoria->id;
        });    

        return view('subcategoria/editSubcategoria', compact('subcategorias', 'subcategoria'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategoria $subcategoria)
    {
        $request->validate([
            'nivel' => ['required', 'string', 'min:5', 'max:50', Rule::unique('subcategorias')->ignore($subcategoria)],            
        ]);

        Subcategoria::where('id', $subcategoria->id)->update($request->except('_token','_method')); //opuesto de except (only)    

        //return redirect() -> route('subcategoria.create');
        return redirect('/subcategoria'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategoria $subcategoria)
    {
        $subcategoria -> delete();

        return redirect('/subcategoria');
    }
}
