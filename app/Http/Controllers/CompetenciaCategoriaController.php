<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Competencia;
use App\Models\CompetenciaCategoria;
use App\Models\CompetenciaSubcategoria;
use App\Models\Subcategoria;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompetenciaCategoriaController extends Controller
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
    public function create(Competencia $competencia)
    {
        if($competencia->publicada){
            
            if($competencia->tipo == 'Cualquiera'){
                $categorias = Categoria::orderBy('tipo', 'asc')->orderBy('name', 'asc')->get();
            }
            elseif($competencia->tipo == 'Equipos'){
                $categorias = Categoria::where('tipo','Equipos')
                ->orderBy('name', 'asc')->get();
            }
            elseif($competencia->tipo == 'Proyectos'){
                $categorias = Categoria::where('tipo','Proyectos')
                ->orderBy('name', 'asc')->get();
            }
    
            if($categorias->count() > 0){
                // Recuperar los IDs de categorías ya registradas en competenciacategorias
                $competenciacategorias = CompetenciaCategoria::pluck('categoria_id')->toArray();
        
                // Filtrar las categorías para excluir las que ya están registradas
                $categorias = $categorias->filter(function ($categoria) use ($competenciacategorias) {
                    return !in_array($categoria->id, $competenciacategorias);
                });  

                if($categorias->count() > 0){
                    return view('competenciacategoria/createcompetenciacategoria', compact('competencia', 'categorias'));
                }
                else{
                    return redirect() -> route('competencia.show', $competencia);    
                }
            }   
            else{
                    return redirect() -> route('competencia.show', $competencia);    
            }                      
    
            
            //$competencia->diasrestantes = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($competencia->fecha), false);
    
            //return view('competenciacategoria/createcompetenciacategoria', compact('competencia', 'categorias'));
        }
        else{
            return redirect('/competencia/draft');
        }        
    }


    public function createdraft(Competencia $competencia)
    {        
        if(!$competencia->publicada){

            if($competencia->tipo == 'Cualquiera'){
                $categorias = Categoria::orderBy('tipo', 'asc')->orderBy('name', 'asc')->get();
            }
            elseif($competencia->tipo == 'Equipos'){
                $categorias = Categoria::where('tipo','Equipos')
                ->orderBy('name', 'asc')->get();
            }
            elseif($competencia->tipo == 'Proyectos'){
                $categorias = Categoria::where('tipo','Proyectos')
                ->orderBy('name', 'asc')->get();
            }

            if($categorias->count() > 0){
                // Recuperar los IDs de categorías ya registradas en competenciacategorias
                $competenciacategorias = CompetenciaCategoria::pluck('categoria_id')->toArray();
        
                // Filtrar las categorías para excluir las que ya están registradas
                $categorias = $categorias->filter(function ($categoria) use ($competenciacategorias) {
                    return !in_array($categoria->id, $competenciacategorias);
                });  

                if($categorias->count() > 0){
                    return view('competenciacategoria/createcompetenciacategoria', compact('competencia', 'categorias'));
                }
                else{
                    return redirect() -> route('competencia.showdraft', $competencia);    
                }
            }   
            else{
                    return redirect() -> route('competencia.showdraft', $competencia);    
            }                             
    
            
            //$competencia->diasrestantes = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($competencia->fecha), false);
    
            //return view('competenciacategoria/createcompetenciacategoria', compact('competencia', 'categorias'));
        }
        else{
            return redirect('/competencia');
        }        
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Competencia $competencia)
    {

        // Agregar la validación condicional
        if($request->registro_personalizado){
            if(!$request->inicio_registros){
                session()->flash('missing_fecha', true);
                $fecha_errors = true;
            }

            if(!$request->fin_registros){
                session()->flash('missing_fecha_fin', true);
                $fecha_errors = true;
            }            
        }else{
            $fecha_errors = false;
        }

        $request->validate([
            'inicio_registros' => ['date','before:' . \Carbon\Carbon::parse($competencia->fecha)->format('Y-m-d'),],
            'fin_registros' => 'date|after:fecha',
            //'fecha' => 'required'
        ]);
        
        if ($fecha_errors) {
            //dd($request->all());                  
            return redirect()->back()->withInput();
        }

        $competenciacategoria = new CompetenciaCategoria();

        // Crear el registro en la base de datos
        
        $competenciacategoria->competencia_id = $competencia->id;
        $competenciacategoria->categoria_id = $request->categoria_id;
        $competenciacategoria->costo = $request->costo;
        

        if($request->registro_personalizado){
            $competenciacategoria->registro_personalizado = true;
            $competenciacategoria->inicio_registros = $request->inicio_registros;
            $competenciacategoria->fin_registros = $request->fin_registros;  
        }else{
            $competenciacategoria->registro_personalizado = false;
        }   


        //dd($registrojuez->id);
        
        $competenciacategoria->save();


        /*if($competencia->publicada == 1){ // RECUERDA DE ENVIAR A CATEGORIA SHOW EN EL EDIT
            return redirect() -> route('competencia.show', $competencia);    
        }
        else{
            return redirect() -> route('competencia.showdraft', $competencia);    
        }  */   
 

        // Verificar la acción seleccionada
        if ($request->action == "Registrar Categoría") {
            if($competencia->publicada == 1){ // RECUERDA DE ENVIAR A CATEGORIA SHOW EN EL EDIT
                return redirect()->route('competencia.show', $competencia);  
            }
            else{
                return redirect()->route('competencia.showdraft', $competencia);
            } 
        }
        else{
            $categoria = Categoria::findOrFail($request->categoria_id);

            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $categoria->name . '"',
                'texto' => '¡Categoría Agregada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);

            if($competencia->publicada == 1){ // RECUERDA DE ENVIAR A CATEGORIA SHOW EN EL EDIT
                return redirect()->route('competenciacategoria.create', $competencia);  
            }
            else{
                return redirect()->route('competenciacategoria.createdraft', $competencia);      
            }      
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        if($competencia->publicada){              
            
            $subcategorias = Subcategoria::all();                        

            if($subcategorias->count() > 0){
                // Recuperar los IDs de categorías ya registradas en competenciacategorias
                $competenciasubcategorias = CompetenciaSubcategoria::pluck('nivel')->toArray();
        
                // Filtrar las categorías para excluir las que ya están registradas
                $subcategorias = $subcategorias->filter(function ($subcategoria) use ($competenciasubcategorias) {
                    return !in_array($subcategoria->nivel, $competenciasubcategorias);
                });  

                if($subcategorias->count() == 0){
                    $todasregistradas = true;
                }
                else{
                    $todasregistradas = false;
                }
            }   
            else{
                $todasregistradas = false;
            }   
    
            $subcategoriascount = $subcategorias->count();


            $competenciaSubcategorias = CompetenciaSubcategoria::where('competencia_categoria_id',$competenciaCategoria->id)
            ->orderBy('nivel', 'asc')->get();

            $cuposrestantes = 0;
            $cupo_ilimitado = False;

            foreach ($competenciaSubcategorias as $competenciaSubcategoria) {
                if($competenciaSubcategoria->limite_inscripciones > 0){
                    $competenciaSubcategoria->cuposrestantes = $competenciaSubcategoria->limite_inscripciones - $competenciaSubcategoria->total_inscritos;

                    $cuposrestantes = $cuposrestantes + $competenciaSubcategoria->cuposrestantes;
                }          
                else{
                    $cupo_ilimitado = True;
                }     
            }

            $categoria = Categoria::findOrFail($competenciaCategoria->categoria_id);
    
            return view('competenciacategoria/showCompetenciacategoria',compact('competencia', 'categoria', 'competenciaCategoria', 'subcategoriascount', 'todasregistradas', 'competenciaSubcategorias', 'cuposrestantes', 'cupo_ilimitado'));
        }
        else{
            return redirect('/competencia/draft');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompetenciaCategoria $competenciaCategoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompetenciaCategoria $competenciaCategoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetenciaCategoria $competenciaCategoria)
    {
        //
    }
}
