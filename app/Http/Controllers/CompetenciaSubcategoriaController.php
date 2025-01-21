<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Competencia;
use App\Models\CompetenciaCategoria;
use App\Models\CompetenciaSubcategoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class CompetenciaSubcategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        if($competencia->publicada){              
            
            $subcategorias = Subcategoria::orderBy('nivel', 'asc')->get();                      

            if($subcategorias->count() > 0){
                // Recuperar los IDs de categorías ya registradas en competenciacategorias
                $competenciasubcategorias = CompetenciaSubcategoria::pluck('nivel')->toArray();
        
                // Filtrar las categorías para excluir las que ya están registradas
                $subcategorias = $subcategorias->filter(function ($subcategoria) use ($competenciasubcategorias) {
                    return !in_array($subcategoria->nivel, $competenciasubcategorias);
                });  
                
                $categoria = Categoria::findOrFail($competenciaCategoria->competencia_id);                

                if($subcategorias->count() > 0){
                    return view('competenciasubcategoria/createcompetenciasubcategoria', compact('competencia', 'competenciaCategoria', 'categoria', 'subcategorias'));
                }
                else{
                    return redirect() -> route('competenciacategoria.show', [$competencia, $competenciaCategoria]);    
                }
            }   
            else{
                return redirect() -> route('competenciacategoria.show', [$competencia, $competenciaCategoria]); 
            } 
        }
        else{
            return redirect('/competencia/draft');
        }
    }

    public function createdraft(Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CompetenciaSubcategoria $competenciaSubcategoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompetenciaSubcategoria $competenciaSubcategoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompetenciaSubcategoria $competenciaSubcategoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetenciaSubcategoria $competenciaSubcategoria)
    {
        //
    }
}
