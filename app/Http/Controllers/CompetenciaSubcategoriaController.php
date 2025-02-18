<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Competencia;
use App\Models\CompetenciaCategoria;
use App\Models\CompetenciaSubcategoria;
use App\Models\Subcategoria;
use Carbon\Carbon;
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
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){
        
            if($competencia->publicada){              
            
                $subcategorias = Subcategoria::orderBy('nivel', 'asc')->get();                      
    
                if($subcategorias->count() > 0){
                    // Recuperar los IDs de categorías ya registradas en competenciacategorias                
                    $competenciasubcategorias = CompetenciaSubcategoria::where('competencia_categoria_id',$competenciaCategoria->id)->pluck('nivel')->toArray();
            
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
        else{
            return redirect() -> route('competenciacategoria.show', [$competencia, $competenciaCategoria]); 
        } 
    }

    public function createdraft(Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){
    
            if(!$competencia->publicada){
            
                $subcategorias = Subcategoria::orderBy('nivel', 'asc')->get();                      
    
                if($subcategorias->count() > 0){
                    // Recuperar los IDs de categorías ya registradas en competenciacategorias                
                    $competenciasubcategorias = CompetenciaSubcategoria::where('competencia_categoria_id',$competenciaCategoria->id)->pluck('nivel')->toArray();
            
                    // Filtrar las categorías para excluir las que ya están registradas
                    $subcategorias = $subcategorias->filter(function ($subcategoria) use ($competenciasubcategorias) {
                        return !in_array($subcategoria->nivel, $competenciasubcategorias);
                    });  
                    
                    $categoria = Categoria::findOrFail($competenciaCategoria->competencia_id);                
    
                    if($subcategorias->count() > 0){
                        return view('competenciasubcategoria/createcompetenciasubcategoria', compact('competencia', 'competenciaCategoria', 'categoria', 'subcategorias'));
                    }
                    else{
                        return redirect() -> route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]);    
                    }
                }   
                else{
                    return redirect() -> route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]); 
                } 
            }
            else{
                return redirect('/competencia');
            }
        }   
        else{
            return redirect() -> route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]); 
        }      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        $costo_errors = false;

        // Agregar la validación condicional
        if($request->costo_personalizado){
            if(!$request->costo && $request->costo != 0){
                session()->flash('missing_costo', true);
                $costo_errors = true;
            }       
        }

        //dd($request->all());

        $request->validate([
            'costo' => ['integer','min:0',],
            'limite_inscripciones' => ['nullable', 'integer','min:2',],
            'min_participantes' => ['required','integer','min:1',],  
            'max_participantes' => ['required','integer','min:1',],          
            //'fecha' => 'required'
        ]);
        
        if ($costo_errors) {
            //dd($request->all());                  
            return redirect()->back()->withInput();
        }

        $competenciasubcategoria = new CompetenciaSubcategoria();

        // Crear el registro en la base de datos
        
        $competenciasubcategoria->competencia_categoria_id = $competenciaCategoria->id;
        $competenciasubcategoria->nivel = $request->nivel;
        $competenciasubcategoria->min_participantes = $request->min_participantes;
        $competenciasubcategoria->max_participantes = $request->max_participantes;

        if($request->costo_personalizado){
            $competenciasubcategoria->costo_personalizado = true;
            $competenciasubcategoria->costo = $request->costo;            
        }else{
            $competenciasubcategoria->costo_personalizado = false;
        }   

        if($request->limite_inscripciones){
            $competenciasubcategoria->limite_inscripciones = $request->limite_inscripciones;                   
        }  

        //dd($registrojuez->id);
        
        $competenciasubcategoria->save();


        /*if($competencia->publicada == 1){ // RECUERDA DE ENVIAR A CATEGORIA SHOW EN EL EDIT
            return redirect() -> route('competencia.show', $competencia);    
        }
        else{
            return redirect() -> route('competencia.showdraft', $competencia);    
        }  */   
 

        // Verificar la acción seleccionada
        if ($request->action == "Registrar subcategoría") {
            if($competencia->publicada == 1){ // RECUERDA DE ENVIAR A CATEGORIA SHOW EN EL EDIT                
                return redirect()->route('competenciacategoria.show', [$competencia, $competenciaCategoria]);  
            }
            else{
                return redirect()->route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]);   
            } 
        }
        else{            

            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $request->nivel . '"',
                'texto' => '¡Nivel de participación Agregado Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);

            if($competencia->publicada == 1){ // RECUERDA DE ENVIAR A CATEGORIA SHOW EN EL EDIT
                return redirect()->route('competenciasubcategoria.create', [$competencia, $competenciaCategoria]);  
            }
            else{
                return redirect()->route('competenciasubcategoria.createdraft', [$competencia, $competenciaCategoria]);      
            }      
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Competencia $competencia, CompetenciaCategoria $competenciaCategoria, CompetenciaSubcategoria $competenciaSubcategoria)
    {
        if($competencia->publicada){
            
        }
        else{
            return redirect('/competencia/draft');
        }
    }

    public function showdraft(Competencia $competencia)
    {
        if(!$competencia->publicada){
            
        }        
        elseif(!$competencia->publicada && $competencia->fecha <= Carbon::now()->startOfDay()){
            // Mostrar detalles de competencias expiradas
            //return view('competencia/showexpiredcompetencia',compact('competencia'));
        }
        else{
            return redirect('/competencia');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competencia $competencia, CompetenciaCategoria $competenciaCategoria, CompetenciaSubcategoria $competenciaSubcategoria)
    {
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){
    
            if($competencia->publicada){              
            
                $subcategorias = Subcategoria::orderBy('nivel', 'asc')->get();         
    
                if($subcategorias->count() > 0){
                    // Recuperar los IDs de categorías ya registradas en competenciacategorias                
                    $competenciasubcategorias = CompetenciaSubcategoria::where('competencia_categoria_id',$competenciaCategoria->id)
                    ->where('nivel', '!=', $competenciaSubcategoria->nivel)->pluck('nivel')->toArray();
            
                    // Filtrar las categorías para excluir las que ya están registradas
                    $subcategorias = $subcategorias->filter(function ($subcategoria) use ($competenciasubcategorias) {
                        return !in_array($subcategoria->nivel, $competenciasubcategorias);
                    });  
                    
                    $categoria = Categoria::findOrFail($competenciaCategoria->competencia_id);                
    
                    return view('competenciasubcategoria/editcompetenciasubcategoria', compact('competencia', 'competenciaCategoria', 'competenciaSubcategoria', 'categoria', 'subcategorias'));              
                }   
                else{
                    return redirect() -> route('competenciacategoria.show', [$competencia, $competenciaCategoria]); 
                } 
            }
            else{
                return redirect('/competencia/draft');
            }
        }
        else{
            return redirect() -> route('competenciacategoria.show', [$competencia, $competenciaCategoria]); 
        }
    }

    public function editdraft(Competencia $competencia, CompetenciaCategoria $competenciaCategoria, CompetenciaSubcategoria $competenciaSubcategoria)
    {
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){
    
            if(!$competencia->publicada){
            
                $subcategorias = Subcategoria::orderBy('nivel', 'asc')->get();         
    
                if($subcategorias->count() > 0){
                    // Recuperar los IDs de categorías ya registradas en competenciacategorias                
                    $competenciasubcategorias = CompetenciaSubcategoria::where('competencia_categoria_id',$competenciaCategoria->id)
                    ->where('nivel', '!=', $competenciaSubcategoria->nivel)->pluck('nivel')->toArray();
            
                    // Filtrar las categorías para excluir las que ya están registradas
                    $subcategorias = $subcategorias->filter(function ($subcategoria) use ($competenciasubcategorias) {
                        return !in_array($subcategoria->nivel, $competenciasubcategorias);
                    });  
                    
                    $categoria = Categoria::findOrFail($competenciaCategoria->competencia_id);                
    
                    return view('competenciasubcategoria/editcompetenciasubcategoria', compact('competencia', 'competenciaCategoria', 'competenciaSubcategoria', 'categoria', 'subcategorias'));              
                }   
                else{
                    return redirect() -> route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]); 
                } 
            }
            else{
                return redirect('/competencia');
            }
        }
        else{
            return redirect() -> route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]); 
        } 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Competencia $competencia, CompetenciaCategoria $competenciaCategoria, CompetenciaSubcategoria $competenciaSubcategoria)
    {
        $costo_errors = false;

        // Agregar la validación condicional
        if($request->costo_personalizado){
            if(!$request->costo && $request->costo != 0){
                session()->flash('missing_costo', true);
                $costo_errors = true;
            }       
        }

        //dd($request->all());

        $request->validate([
            'costo' => ['integer','min:0',],
            'limite_inscripciones' => ['nullable', 'integer','min:2',],
            'min_participantes' => ['required','integer','min:1',],  
            'max_participantes' => ['required','integer','min:1',],          
            //'fecha' => 'required'
        ]);
        
        if ($costo_errors) {
            //dd($request->all());                  
            return redirect()->back()->withInput();
        }


        if($request->costo_personalizado){
            $request -> merge([            
                'costo_personalizado' => true,
            ]);
        }else{
            $request -> merge([            
                'costo_personalizado' => false,
                'costo' => null,                
            ]);
        }   

        CompetenciaSubcategoria::where('id', $competenciaSubcategoria->id)->update($request->except('_token','_method','action')); 


        // Configura los datos para la notificación
        session()->flash('alerta', [                
            'texto' => '¡Subcategoría Actualizada Exitosamente!',
            'icono' => 'success',
            'tiempo' => 2000,
            'botonConfirmacion' => false,
        ]);
        
        if($competencia->publicada == 1){            
            return redirect() -> route('competenciacategoria.show', [$competencia, $competenciaCategoria]);  
        }
        else{                           
            return redirect() -> route('competenciacategoria.showdraft', [$competencia, $competenciaCategoria]); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competencia $competencia, CompetenciaCategoria $competenciaCategoria, CompetenciaSubcategoria $competenciaSubcategoria)
    {
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){
    
            $subcategoria = $competenciaSubcategoria -> nivel;

            $competenciaSubcategoria -> delete();

            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $subcategoria . '"',
                'texto' => '¡Categoría Eliminada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);
        }
        

        if($competencia->publicada == 1){ // RECUERDA DE ENVIAR A CATEGORIA SHOW EN EL EDIT
            return redirect()->route('competencia.show', $competencia);  
        }
        else{
            return redirect()->route('competencia.showdraft', $competencia);
        } 
    }
}
