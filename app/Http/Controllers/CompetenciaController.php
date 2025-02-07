<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Categoria;
use App\Models\Competencia;
use App\Models\CompetenciaCategoria;
use App\Models\Equipo;
use App\Models\Proyecto;
use Carbon\Carbon;
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
        $this->middleware('auth')->except(['index','show', 'previous', 'showprevious']); //excepto estas necesitan iniciar sesion 

        $this->middleware('can:only-superadmin')->except('index', 'show', 'previous', 'showprevious');
    }
     
    //otra variante es "only" para autenticar solo aquellas que notros queramos 
    

    public function index()
    {
        // Competencias futuras
        $competencias = Competencia::where('publicada',1)->where('fecha', '>', Carbon::now()->startOfDay())
        ->orderBy('fecha', 'asc')->get(); // Mas proxima a mas lejana | // Si quiero ordenarlos de mayor a menos usar desc
        
        // Competencias en progreso
        $actuales = Competencia::where('publicada',1)->where('fecha', '<=', Carbon::now()->startOfDay())
        ->where('fecha_fin', '>=', Carbon::now()->endOfDay())->orderBy('fecha', 'asc')->get(); // Mas proxima a mas lejana | // Si quiero ordenarlos de mayor a menos usar desc   
        
        // Cuenta de competencias pasadas
        $pasadas = Competencia::where('publicada',1)->where('fecha_fin', '<', Carbon::now()->startOfDay())->get();

        $pasadascount = $pasadas->count();

        // Cuenta de borradores de competencias no publicadas
        /*$borradores = Competencia::where('publicada',0)->get(); 
        $borradorescount = $borradores->count();*/

        // Cuenta de competencias borradas
        /*$borradas = Competencia::onlyTrashed()->where('publicada',1)->get();

        $borradascount = $borradas->count();*/

        return view('competencia/indexCompetencia', compact('competencias','actuales','pasadascount'));
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
            'imagen' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:15360'], // Máximo 15 Mb
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

        // Nombre de competencia unico (VALIDATE UNIQUE)
        $competenciaDraftRegistrada = Competencia::where('name', $request->name)->where('publicada', 0)
        ->where('fecha', '>', Carbon::now()->startOfDay())->first();

        $competenciaRegistrada = Competencia::where('name', $request->name)->where('publicada', 1)->first();        
        
        if ($competenciaDraftRegistrada || $competenciaRegistrada) {
            return redirect()->back()->withErrors(['name' => 'Este nombre ya ha sido registrado para otra competencia.'])->withInput();
        }
        

        // Generar el enlace de Google Maps
        $googleMapsLink = "https://www.google.com/maps?q={$request->latitud},{$request->longitud}";
        
        $competencia = new Competencia();

        // Crear el registro en la base de datos
        
        $competencia->name = $request->name;
        $competencia->descripcion = $request->descripcion;
        $competencia->fecha = $request->fecha;
        $competencia->fecha_fin = Carbon::parse($request->fecha)->addDays(($request->duracion)-1); // ($request->duracion)-1
        $competencia->duracion = $request->duracion;
        $competencia->tipo = $request->tipo;
        $competencia->inicio_registros = $request->inicio_registros;
        $competencia->fin_registros = $request->fin_registros;

        $competencia->sede = $request->sede;
        $competencia->ubicacion = $request->ubicacion;
        $competencia->latitud = $request->latitud;
        $competencia->longitud = $request->longitud;
        $competencia->mapa_link = $googleMapsLink;
        $competencia->ubicacion_imagen = $request->file('imagen')->storeAs('public/imagenes_competencias', 'Portada_'.$request->name.'.'. $request->file('imagen')->extension());


        //dd($registrojuez->id);
        
        $competencia->save();

        return redirect()->route('competencia.draft');

    }

    /**
     * Display the specified resource.
     */
    public function show(Competencia $competencia)
    {
        //$competencias = Competencia::all();

        /*if (Gate::allows('only-superadmin')) {
            $equipos = Equipo::where('competencia_id',$competencia->id)->get(); 
            $proyectos = Proyecto::where('competencia_id',$competencia->id)->get(); 
            
            return view('competencia/showCompetencia',compact('competencia','equipos','proyectos')); // Listar los proyectos y equipos registrados en esa competencia
        }
        else{
            return view('competencia/showCompetencia',compact('competencia'));
        }*/

        //$asesor = Asesor::where('id',$equipo->asesor_id)->first();

        //return view('competencia/showCompetencia',compact('competencia')); //asesor es el usuario actual a mostrar
        

        if($competencia->publicada){
            if($competencia->tipo == 'Cualquiera'){
                $categorias = Categoria::all();
            }
            elseif($competencia->tipo == 'Equipos'){
                $categorias = Categoria::where('tipo','Equipos')->get();
            }
            elseif($competencia->tipo == 'Proyectos'){
                $categorias = Categoria::where('tipo','Proyectos')->get();
            }

            if($categorias->count() > 0){
                // Recuperar los IDs de categorías ya registradas en competenciacategorias                
                $competenciacategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)->pluck('categoria_id')->toArray();
        
                // Filtrar las categorías para excluir las que ya están registradas
                $categorias = $categorias->filter(function ($categoria) use ($competenciacategorias) {
                    return !in_array($categoria->id, $competenciacategorias);
                });  

                if($categorias->count() == 0){
                    $todasregistradas = true;
                }
                else{
                    $todasregistradas = false;
                }
            }   
            else{
                $todasregistradas = false;
            }   
    
            $categoriascount = $categorias->count();

            $competenciaCategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)
            ->orderBy('inicio_registros', 'asc')->orderBy('fin_registros', 'asc')->get();

            // lte(): Menor o igual a.
            $competencia->enProgreso = Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay());
            
            // lt(): Menor que.
            $competencia->pasada = Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay());
    
            return view('competencia/showCompetencia',compact('competencia', 'categoriascount', 'todasregistradas', 'competenciaCategorias'));
        }
        else{
            return redirect('/competencia/draft');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competencia $competencia)
    {
        if($competencia->publicada){
            $competencia->enProgreso = Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay());
        
            return view('competencia/editcompetencia',compact('competencia')); //formulario para editar la base, asesor es el usuario a editar
        }else{
            return redirect('/competencia/draft');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Competencia $competencia)
    {
        //dd($request);

        //dd($request->all());

        if ($request->hasFile('imagen')) {
            // Guardar archivo en la sesión si existe            
            session()->flash('imagen_cargada', true);

            /*$archivo = $request->file('imagen');
            session()->flash('imagen_info', [
                'nombre' => $archivo->getClientOriginalName(),
                'tipo' => $archivo->getMimeType(),
            ]);*/
        }

        $edit_errors = false;

        // Agregar la validación condicional
        if (!$competencia->enProgreso) {
            if(!$request->fecha){
                session()->flash('missing_fecha', true);
                $edit_errors = true;
            }

            if(!$request->tipo){
                session()->flash('missing_tipo', true);
                $edit_errors = true;
            }

            if(!$request->inicio_registros){
                session()->flash('missing_fecha_inicio', true);
                $edit_errors = true;
            }

            if(!$request->fin_registros){
                session()->flash('missing_fecha_fin', true);
                $edit_errors = true;
            }
        }

        $request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            //'name' => ['required', 'string', 'min:5', 'max:50', Rule::unique('competencias')->ignore($competencia)],
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'fecha' => ['date', 'before_or_equal:' . now()->addYears(2)->format('Y-m-d')],
            'duracion' => ['required','integer','min:1','max:100'],
            //'asesor_id' => ['required', 'not_in:Selecciona una opción'],
            //'tipo' => ['required'],
            //'categoria_id' => ['required'],
            'imagen' => ['image', 'mimes:png,jpg,jpeg', 'max:5120'], // Máximo 5 Mb
        ]);

        if ($edit_errors) {
            //dd($request->all());                  
            return redirect()->back()->withInput();
        }


        // Nombre de competencia unico (VALIDATE UNIQUE)
        $competenciaDraftRegistrada = Competencia::where('id', '!=', $competencia->id)->where('name', $request->name)
        ->where('publicada', 0)->where('fecha', '>', Carbon::now()->startOfDay())->first();

        $competenciaRegistrada = Competencia::where('id', '!=', $competencia->id)->where('name', $request->name)
        ->where('publicada', 1)->first();                
        
        if ($competenciaDraftRegistrada || $competenciaRegistrada) {
            return redirect()->back()->withErrors(['name' => 'Este nombre ya ha sido registrado para otra competencia.'])->withInput();
        }


        // Generar el enlace de Google Maps
        $googleMapsLink = "https://www.google.com/maps?q={$request->latitud},{$request->longitud}";
    
        $request -> merge([            
            'mapa_link' => $googleMapsLink,
        ]);

        if ($request->hasFile('imagen')) {
            //dd($request);
            $request -> merge([
                'ubicacion_imagen' => $request->file('imagen')->storeAs('public/imagenes_competencias', 'Portada_'.$request->name.'.'. $request->file('imagen')->extension()),                
            ]);
        }         
        
        //dd($request->all());

        Competencia::where('id', $competencia->id)->update($request->except('_token','_method','imagen'));

        // Actualizar tabla pivote con los nuevos registros  
        //$competencia->categorias()->sync($request->input('categoria_id'));

        // Insertar en la tabla pivote relacion m:n --> PENDIENTE FINAL
        //$competencia->categorias()->attach($request->categoria_id); //detach() elimina de la lista el usuario que le pasemos


        //Competencia::where('id', $competencia->id)->update($request->except('_token','_method')); //opuesto de except (only)

        //return redirect() -> route('categoria.show', $categoria); //esto corresponde a el listado de route:list 
        
        //return redirect() -> route('competencia.index'); //esto corresponde a el listado de route:list 

        /*$previousUrl = session('_custom_previous.url');

        //return redirect() -> route('competencia.show', $competencia);
        return redirect($previousUrl);*/

        //return redirect($request->ruta); 
        
        // Configura los datos para la notificación
        session()->flash('alerta', [                
            'texto' => '¡Competencia Actualizada Exitosamente!',
            'icono' => 'success',
            'tiempo' => 2000,
            'botonConfirmacion' => false,
        ]);
        
        if($competencia->publicada == 1){
            return redirect() -> route('competencia.show', $competencia);    
        }
        else{
            return redirect() -> route('competencia.showdraft', $competencia);    
        }
    }


//=======================================================================================================================>


    public function draft()
    {
        // Proximas competencias
        $competencias = Competencia::where('publicada',0)->where('fecha', '>', Carbon::now()->startOfDay())
        ->orderBy('fecha', 'asc')->get(); // Mas proxima a mas lejana | // Si quiero ordenarlos de mayor a menos usar desc

        // Competencias que ya pasaron o estan pasando
        $expiradas = Competencia::where('publicada',0)->where('fecha', '<=', Carbon::now()->startOfDay())
        ->orderBy('fecha', 'desc')->get(); // Mas lejana a mas proxima | // Si quiero ordenarlos de menor a mayor usar asc

        // Cuenta de competencias borradas
        $borrados = Competencia::onlyTrashed()->where('publicada',0)->get();

        $borradoscount = $borrados->count();        

        //$previousUrl = url()->previous();

        /*foreach ($registrojueces as $registro) {
            $registro->diasrestantes = Carbon::now()->diffInDays(Carbon::parse($registro->expiracion_date), false);
        }*/
        // Eager Loading
        //$competencias = Competencia::with('categorias')->get(); // Hace una consulta más

        return view('competencia/draftCompetencia', compact('competencias', 'expiradas', 'borradoscount'));
    }

    public function editdraft(Competencia $competencia)
    {
        if(!$competencia->publicada){
            $competencia->enProgreso = Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay());
        
            return view('competencia/editcompetencia',compact('competencia')); //formulario para editar la base, asesor es el usuario a editar
        }
        else{
            return redirect('/competencia');
        }        
    }

    public function showdraft(Competencia $competencia)
    {
        if(!$competencia->publicada){
            if($competencia->tipo == 'Cualquiera'){
                $categorias = Categoria::all();
            }
            elseif($competencia->tipo == 'Equipos'){
                $categorias = Categoria::where('tipo','Equipos')->get();
            }
            elseif($competencia->tipo == 'Proyectos'){
                $categorias = Categoria::where('tipo','Proyectos')->get();
            }      
            
            if($categorias->count() > 0){
                // Recuperar los IDs de categorías ya registradas en competenciacategorias                                
                $competenciacategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)->pluck('categoria_id')->toArray();
        
                // Filtrar las categorías para excluir las que ya están registradas
                $categorias = $categorias->filter(function ($categoria) use ($competenciacategorias) {
                    return !in_array($categoria->id, $competenciacategorias);
                });  

                if($categorias->count() == 0){
                    $todasregistradas = true;
                }
                else{
                    $todasregistradas = false;
                }
            }
            else{
                $todasregistradas = false;
            }             
    
            $categoriascount = $categorias->count();            

            $competenciaCategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)
            ->orderBy('inicio_registros', 'asc')->orderBy('fin_registros', 'asc')->get();
           
            // lte(): Menor o igual a.
            $competencia->enProgreso = Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay());
            
            // lt(): Menor que.
            $competencia->pasada = Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay());
    
            return view('competencia/showCompetencia',compact('competencia', 'categoriascount', 'todasregistradas', 'competenciaCategorias'));
        }        
        elseif(!$competencia->publicada && $competencia->fecha <= Carbon::now()->startOfDay()){
            // Mostrar detalles de competencias expiradas
            //return view('competencia/showexpiredcompetencia',compact('competencia'));
        }
        else{
            return redirect('/competencia');
        }
    }

    public function publicar(Competencia $competencia) 
    {

        if($competencia->publicada == true){
            $competencia->publicada = false; 
            $competencia->save();

            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $competencia->name . '"',                
                'texto' => '¡Competencia Deshabilitada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);

            return redirect('/competencia'); // Pendiente enviar a show
        }
        else{
            $competencia->publicada = true; 
            $competencia->save();

            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $competencia->name . '"',
                'texto' => '¡Competencia Publicada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);

            return redirect('/competencia/draft'); // Pendiente enviar a show
        }        

        /* Sweet Alert Pendiente (NO BORRAR) */
        /*return redirect('/competencia')->with('notificacion', [
            'titulo' => 'Registro exitoso',
            'mensaje' => 'Tu cuenta se ha creado correctamente.',
        ]);*/

        //return redirect('/competencia/draft'); // Pendiente enviar a show
    }

    /*public function disabled(Competencia $competencia) 
    {

        $competencia->publicada = false; // Cambia publicada de true a false
        $competencia->save();            

        return redirect('/competencia');  // Pendiente enviar a show
    }*/

    public function previous()
    {        

        if (auth()->check()) { // Verifica si el usuario está logueado
            
            $user = auth()->user();

            if ($user->rol == 1 || $user->rol == 2) {
                // Competencias pasadas
                $competencias = Competencia::where('publicada',1)->where('fecha_fin', '<', Carbon::now()->startOfDay())
                ->orderByRaw('YEAR(fecha) DESC, MONTH(fecha) ASC')->get() // Ordena por año y luego por mes        
                ->groupBy(function ($competencia) {
                    return date('Y', strtotime($competencia->fecha)); // Agrupa por año
                });
            } else{
                // Competencias pasadas otros usuarios
                $competencias = Competencia::where('publicada',1)->where('oculta',0)
                ->where('fecha_fin', '<', Carbon::now()->startOfDay())
                ->orderByRaw('YEAR(fecha) DESC, MONTH(fecha) ASC')->get() // Ordena por año y luego por mes        
                ->groupBy(function ($competencia) {
                    return date('Y', strtotime($competencia->fecha)); // Agrupa por año
                });
            }
        } else {
            // Competencias pasadas no logueados
            $competencias = Competencia::where('publicada',1)->where('oculta',0)
            ->where('fecha_fin', '<', Carbon::now()->startOfDay())
            ->orderByRaw('YEAR(fecha) DESC, MONTH(fecha) ASC')->get() // Ordena por año y luego por mes        
            ->groupBy(function ($competencia) {
                return date('Y', strtotime($competencia->fecha)); // Agrupa por año
            });
        }

        return view('competencia/previousCompetencia', compact('competencias')); 
    }

    public function ocultar(Competencia $competencia) 
    {
        if($competencia->oculta == true){
            $competencia->oculta = false; // Cambia el rol a 1
            $competencia->save();

            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $competencia->name . '"',                
                'texto' => '¡Competencia Mostrada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);
        }
        else{
            $competencia->oculta = true; // Cambia el rol a 1
            $competencia->save();

            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $competencia->name . '"',
                'texto' => '¡Competencia Ocultada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);
        }        

        return redirect('/competencia/historial'); // Pendiente enviar a show
    }

    public function showprevious(Competencia $competencia)
    {
        if($competencia->publicada){
            if($competencia->tipo == 'Cualquiera'){
                $categorias = Categoria::all();
            }
            elseif($competencia->tipo == 'Equipos'){
                $categorias = Categoria::where('tipo','Equipos')->get();
            }
            elseif($competencia->tipo == 'Proyectos'){
                $categorias = Categoria::where('tipo','Proyectos')->get();
            }

            if($categorias->count() > 0){
                // Recuperar los IDs de categorías ya registradas en competenciacategorias                
                $competenciacategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)->pluck('categoria_id')->toArray();
        
                // Filtrar las categorías para excluir las que ya están registradas
                $categorias = $categorias->filter(function ($categoria) use ($competenciacategorias) {
                    return !in_array($categoria->id, $competenciacategorias);
                });  

                if($categorias->count() == 0){
                    $todasregistradas = true;
                }
                else{
                    $todasregistradas = false;
                }
            }   
            else{
                $todasregistradas = false;
            }   
    
            $categoriascount = $categorias->count();

            $competenciaCategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)
            ->orderBy('inicio_registros', 'asc')->orderBy('fin_registros', 'asc')->get();

            // lte(): Menor o igual a.
            $competencia->enProgreso = Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay());
            
            // lt(): Menor que.
            $competencia->pasada = Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay());
    
            return view('competencia/showCompetencia',compact('competencia', 'categoriascount', 'todasregistradas', 'competenciaCategorias'));
        }
        else{
            return redirect('/competencia/draft');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Competencia $competencia)
    {

        //dd($competencia);

        //dd($request->ruta);
        
        //$competencia->categorias()->detach(); // Eliminar registros de tabla pivote

        // Soft delete tabla pivote
        
        
        //$competencia->categorias()->update(['deleted_at' => now()]);        

        //dd($juez);

        
        //$juez->user->delete();  //Relacion 1:1

        // lte(): Menor o igual a.
        // lt(): Menor que.
        if(!Carbon::parse($competencia->fecha)->lte(Carbon::now()->startOfDay()) 
        && !Carbon::parse($competencia->fecha_fin)->lt(Carbon::now()->endOfDay())){

            $nombreCompetencia = $competencia->name;


            $competencia->publicada = false; // Cambia publicada de true a false
            
            $competencia->save();

            $competencia -> delete();        

            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $nombreCompetencia . '"',
                'texto' => '¡Competencia Eliminada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);
        }


        return redirect($request->ruta);
    } 
    
    // Eliminar todos los registros expirados
    public function destroyexpiradas()
    {

        // Elimina los registros expirados
        Competencia::where(function ($query){
            $query->where('publicada',0)->where('fecha', '<=', Carbon::now()->startOfDay());            
        })->delete();

        session()->flash('alerta', [                
            'texto' => '¡Borradores Expirados Eliminados Exitosamente!',
            'icono' => 'success',
            'tiempo' => 2000,
            'botonConfirmacion' => false,
        ]);

        // Redirige con un mensaje de éxito
        return redirect('/competencia/draft');
    }

    public function trashed()
    {
        // Obtiene todos los registros eliminados

        //dd($jueces);

        // Proximas competencias
        $competencias = Competencia::onlyTrashed()->where('publicada',0)->where('fecha', '>', Carbon::now()->startOfDay())
        ->orderBy('fecha', 'asc')->get(); // Mas proxima a mas lejana | // Si quiero ordenarlos de mayor a menos usar desc

        // Competencias que ya pasaron o estan pasando
        $expiradas = Competencia::onlyTrashed()->where('publicada',0)->where('fecha', '<=', Carbon::now()->startOfDay())
        ->orderBy('fecha', 'desc')->get(); // Mas lejana a mas proxima | // Si quiero ordenarlos de menor a mayor usar asc   


        return view('competencia/trashedCompetencia', compact('competencias', 'expiradas'));
    }

    /**
     * Display the specified delete resource.
     */
    public function showtrashed($competenciaid)
    {        
        $competencia = Competencia::onlyTrashed()->findOrFail($competenciaid);

        if($competencia->tipo == 'Cualquiera'){
            $categorias = Categoria::all();
        }
        elseif($competencia->tipo == 'Equipos'){
            $categorias = Categoria::where('tipo','Equipos')->get();
        }
        elseif($competencia->tipo == 'Proyectos'){
            $categorias = Categoria::where('tipo','Proyectos')->get();
        }

        if($categorias->count() > 0){
            // Recuperar los IDs de categorías ya registradas en competenciacategorias                
            $competenciacategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)->pluck('categoria_id')->toArray();
    
            // Filtrar las categorías para excluir las que ya están registradas
            $categorias = $categorias->filter(function ($categoria) use ($competenciacategorias) {
                return !in_array($categoria->id, $competenciacategorias);
            });              
        }   

        $categoriascount = $categorias->count();

        $competenciaCategorias = CompetenciaCategoria::where('competencia_id',$competencia->id)
        ->orderBy('inicio_registros', 'asc')->orderBy('fin_registros', 'asc')->get();

        return view('competencia/showtrashedCompetencia',compact('competencia', 'categoriascount', 'competenciaCategorias'));
    }

    public function restore($id)
    {

        //dd($id);

        // Busca el registro eliminado por ID
        $competencia = Competencia::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados

        // Nombre de competencia unico (VALIDATE UNIQUE)
        $competenciaDraftRegistrada = Competencia::where('name', $competencia->name)->where('publicada', 0)
        ->where('fecha', '>', Carbon::now()->startOfDay())->first();

        $competenciaRegistrada = Competencia::where('name', $competencia->name)->where('publicada', 1)->first();        
        
        if ($competenciaDraftRegistrada || $competenciaRegistrada) {
            // Configura los datos para la notificación
            session()->flash('cambiarNombre', [
                'titulo' => '"' . $competencia->name . '"',
                'texto' => '¡Ya existe otra competencia con el mismo nombre! No es posible restaurarla.',
                'icono' => 'warning',                
                'confirmButtonText' => 'Cambiar nombre',
                'inputValue' => $competencia->name
            ]);
        }
        else{
            //dd($juez->user()->withTrashed()->first());

            // Restaura el registro
            $competencia->restore();


            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $competencia->name . '"',
                'texto' => '¡Competencia Restaurada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);
        }                          

        return redirect('/competencia/trashed');
    }


    public function updateName(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Busca el registro eliminado por ID
        $competencia = Competencia::onlyTrashed()->findOrFail($id); // Busca solo registros eliminados

        // Nombre de competencia unico (VALIDATE UNIQUE)
        $competenciaDraftRegistrada = Competencia::where('name',  $request->name)->where('publicada', 0)
        ->where('fecha', '>', Carbon::now()->startOfDay())->first();

        $competenciaRegistrada = Competencia::where('name', $request->name)->where('publicada', 1)->first();        
        

        if ($competenciaDraftRegistrada || $competenciaRegistrada) {
            // Configura los datos para la notificación
            session()->flash('cambiarNombre', [
                'titulo' => '"' . $request->name . '"',
                'texto' => '¡Este nombre ya ha sido registrado para otra competencia!',
                'icono' => 'error',                
                'confirmButtonText' => 'Elegir otro nombre',
                'inputValue' => $request->name
            ]);
        }
        else{
            //$competencia->name = $request->input('name');
            $competencia->name = $request->name;
            $competencia->save();

            $competencia->restore();


            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"' . $competencia->name . '"',
                'texto' => '¡Competencia Restaurada Exitosamente!',
                'icono' => 'success',
                'tiempo' => 2000,
                'botonConfirmacion' => false,
            ]);
        }

        //return response()->json(['nombre' => $competencia->nombre], 200);
        //return redirect()->back()->with('success', 'El nombre de la competencia se actualizó correctamente.');
        return redirect('/competencia/trashed');
    }




    /*public function drafttrashed()
    {
        // Obtiene todos los registros eliminados
        
        $jueces = Competencia::onlyTrashed()->get();

        //dd($jueces);

        // Retorna la vista con los registros eliminados
        return view("juez/trashedJuez",compact('jueces')); 
    }*/

    /*public function showtdrafttrashed($id)
    {
        //
    }*/


    /*public function harddestroy(Competencia $juez) 
    {
        //dd($juez->registro_juez);

        $juez->forceDelete();
        
        $juez->user->forceDelete();
        
        $juez->registro_juez->delete();

        return redirect('/competencia');
    }*/

    /*public function disabledharddestroy($id) 
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
    }*/

}
