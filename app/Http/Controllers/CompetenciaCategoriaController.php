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
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){

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
                    $competenciacategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)->pluck('categoria_id')->toArray();
    
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
        else{
            return redirect() -> route('competencia.show', $competencia);    
        }       
    }


    public function createdraft(Competencia $competencia)
    {       
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){

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
                    $competenciacategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)->pluck('categoria_id')->toArray();
    
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
        else{
            return redirect() -> route('competencia.showdraft', $competencia);    
        }   
                
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Competencia $competencia)
    {        
        $fecha_errors = false;
        
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
        if ($request->action == "Registrar categoría") {
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
                'tiempo' => 2500,
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
                $competenciasubcategorias = CompetenciaSubcategoria::where('competencia_categoria_id',$competenciaCategoria->id)->pluck('nivel')->toArray();
        
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

            // lte(): Menor o igual a.
            $competencia->enProgreso = Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay());
            
            // lt(): Menor que.
            $competencia->pasada = Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay());
    
            return view('competenciacategoria/showCompetenciacategoria',compact('competencia', 'categoria', 'competenciaCategoria', 'subcategoriascount', 'todasregistradas', 'competenciaSubcategorias', 'cuposrestantes', 'cupo_ilimitado'));
        }
        else{
            return redirect('/competencia/draft');
        }
    }

    public function showdraft(Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        if(!$competencia->publicada){

            $subcategorias = Subcategoria::all();                        

            if($subcategorias->count() > 0){
                // Recuperar los IDs de categorías ya registradas en competenciacategorias                
                $competenciasubcategorias = CompetenciaSubcategoria::where('competencia_categoria_id',$competenciaCategoria->id)->pluck('nivel')->toArray();
        
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

            // lte(): Menor o igual a.
            $competencia->enProgreso = Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay());
            
            // lt(): Menor que.
            $competencia->pasada = Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay());
    
            return view('competenciacategoria/showCompetenciacategoria',compact('competencia', 'categoria', 'competenciaCategoria', 'subcategoriascount', 'todasregistradas', 'competenciaSubcategorias', 'cuposrestantes', 'cupo_ilimitado'));
        }
        elseif(!$competencia->publicada && $competencia->fecha <= Carbon::now()->startOfDay()){
            // Mostrar detalles de competencias expiradas
            //return view('competencia/showexpiredcompetencia',compact('competencia'));
        }
        else{
            return redirect('/competencia');
        }
    }

    public function showtrashed($competenciaid, $categoriaid){

        $competencia = Competencia::onlyTrashed()->findOrFail($competenciaid);

        $competenciaCategoria = CompetenciaCategoria::findOrFail($categoriaid);
                

        $subcategorias = Subcategoria::all();                        

        if($subcategorias->count() > 0){
            // Recuperar los IDs de categorías ya registradas en competenciacategorias                
            $competenciasubcategorias = CompetenciaSubcategoria::where('competencia_categoria_id',$competenciaCategoria->id)->pluck('nivel')->toArray();
    
            // Filtrar las categorías para excluir las que ya están registradas
            $subcategorias = $subcategorias->filter(function ($subcategoria) use ($competenciasubcategorias) {
                return !in_array($subcategoria->nivel, $competenciasubcategorias);
            });  

        }   

        $subcategoriascount = $subcategorias->count();


        $competenciaSubcategorias = CompetenciaSubcategoria::where('competencia_categoria_id',$competenciaCategoria->id)
        ->orderBy('nivel', 'asc')->get();

        foreach ($competenciaSubcategorias as $competenciaSubcategoria) {
            if($competenciaSubcategoria->limite_inscripciones > 0){
                $competenciaSubcategoria->cuposrestantes = $competenciaSubcategoria->limite_inscripciones - $competenciaSubcategoria->total_inscritos;                
            }              
        }

        $categoria = Categoria::findOrFail($competenciaCategoria->categoria_id);

        return view('competenciacategoria/showtrashedCompetenciacategoria',compact('competencia', 'categoria', 'competenciaCategoria', 'subcategoriascount', 'competenciaSubcategorias'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){
    
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
                    // Recuperar los IDs de categorías ya registradas en competenciacategorias excepto el actual
                    $competenciacategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)
                    ->where('categoria_id', '!=', $competenciaCategoria->categoria_id)->pluck('categoria_id')->toArray();
            
                    // Filtrar las categorías para excluir las que ya están registradas
                    $categorias = $categorias->filter(function ($categoria) use ($competenciacategorias) {
                        return !in_array($categoria->id, $competenciacategorias);
                    });  
                    
                    return view('competenciacategoria/editcompetenciacategoria', compact('competencia', 'competenciaCategoria', 'categorias'));
                }   
                else{
                    return redirect() -> route('competencia.show', $competencia);    
                }                      
            }
            else{
                return redirect('/competencia/draft');
            } 
        }
        else{
            return redirect() -> route('competencia.show', $competencia);    
        }  
    }

    public function editdraft(Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){
    
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
                    // Recuperar los IDs de categorías ya registradas en competenciacategorias excepto el actual
                    $competenciacategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)
                    ->where('categoria_id', '!=', $competenciaCategoria->categoria_id)->pluck('categoria_id')->toArray();
            
                    // Filtrar las categorías para excluir las que ya están registradas
                    $categorias = $categorias->filter(function ($categoria) use ($competenciacategorias) {
                        return !in_array($categoria->id, $competenciacategorias);
                    });  
                    
                    return view('competenciacategoria/editcompetenciacategoria', compact('competencia', 'competenciaCategoria', 'categorias'));
                }   
                else{
                        return redirect() -> route('competencia.showdraft', $competencia);    
                }  
            }
            else{
                return redirect('/competencia');
            }
        }
        else{
            return redirect() -> route('competencia.showdraft', $competencia);    
        } 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {
        //dd($request->all());

        $fecha_errors = false;
        
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
        

        if($request->registro_personalizado){
            $request -> merge([            
                'registro_personalizado' => true,
            ]);
        }else{
            $request -> merge([            
                'registro_personalizado' => false,
                'inicio_registros' => null,
                'fin_registros' => null,
            ]);
        }   

        CompetenciaCategoria::where('id', $competenciaCategoria->id)->update($request->except('_token','_method','action'));     

        // Configura los datos para la notificación
        session()->flash('alerta', [                
            'texto' => '¡Categoría Actualizada Exitosamente!',
            'icono' => 'success',
            'tiempo' => 2500,
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
    public function destroy(Competencia $competencia, CompetenciaCategoria $competenciaCategoria)
    {     
        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){
    
            $categoria = Categoria::find($competenciaCategoria->categoria_id);

            $competenciaCategoria -> delete();

            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $categoria->name . '"',
                'texto' => '¡Categoría Eliminada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2500,
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
