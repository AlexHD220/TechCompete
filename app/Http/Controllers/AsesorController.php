<?php

namespace App\Http\Controllers;

use App\Mail\NotificaAsesorCreado;
use App\Models\Asesor;
use App\Models\Equipo;
use App\Models\Organizacion;
use App\Models\Proyecto;
use App\Models\Usuario; //Insertar datos en la tabla usuarios
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //ID Usuario
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class AsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     // Midelware de iniciar sesion 
     /*public function __construct() //proteger con inicio de sesion aquellas pestañas que yo quiera
     {
        $this->middleware('auth')->except(['index','show']); //excepto estas necesitan iniciar sesion 
     }*/

     
    //otra variante es "only" para autenticar solo aquellas que notros queramos 


    public function __construct()
    {
        $this->middleware('can:only-user')->except('show');
    }

    public function index()
    {
       //$asesores = Asesor::all();

       //Solo los asesores relacionados con ese usuario) (ULTIMO FUNCIONAL)
       $asesores = Asesor::where('user_id',Auth::id())->get(); //registros que solo pertenezcan al usuario logueado

       // Eager Loading
       //$asesores = Asesor::with('user')->where('user_id',Auth::id())->get(); // Hace una consulta más

       // Eager loading viejo (cargar solo la informacion de los asesors con equipos)
       //$asesores = Asesor::with('user:id,name')->with('competencias')->get(); //eager loading (CARGAR TDA LA INFORMACION DE TODOS LOS USUARIO EN UNA SOLACONSULTA, EN LUGAR DE LLAMAR VARIAS VECES A LA BASE DE DATOS ME TRAIGO TODA LA INFORMACION DE JALON)

        //dd($asesores); //para ver que hay en esa variable
        return view("asesor/indexAsesor",compact('asesores')); //<----- regresar vista al llamar al archivo index (asesor)
        //compact es para enviar al archhivo todos los datos de la variable asesores 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$orgs = Organizacion::all();
        //return view('asesor/createAsesor', compact('orgs'));
        return view('asesor/createAsesor');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            //'nombre' => 'required|string|max:255',
            //'telefono' => ['required','min:10','max:10']
            /*'usuario' => ['required', 'string', 'min:5', 'regex:/^[A-Za-z0-9_-]+$/'],*/
            'nombre' => ['required', 'string', 'min:10', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'correo' => ['required', 'string', 'email', 'min:5', 'max:50'],
            'telefono' => ['nullable','numeric','regex:/^\d{10}$/',],
            
            /*'pass' => ['required', 'min:5','max:15', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/']*/

            //'pass' => ['required', 'min:8','max:15', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/']
            /*La contraseña debe tener al menos 8 caracteres y debe contener al menos una letra minúscula, una letra mayúscula, un número y un carácter especial.*/
        ]);
    
        
        
        //Contacto --> a las clases se les nombra con matusculas (modelos)        
        //$usuario->comentario = $request->comentario; 
        //$asesor->pass = $request->pass;
        
        

        //Forma nueva

        // Insertar un dato en el request
        $request->merge(['user_id' => Auth::id()]); //Inyectar el user id en el request

       
        //$asesor = Asesor::create($request->only('id'));

        //dd($request->organizacion_id); //PRUEBA DD

        
        $asesor = Asesor::create($request->all()); // <-- hace todo lo que esta abajo desde new hasta save


        //RELACION MUCHOS A MUCHOS prueba
        //Tabla pivote
        /*$asesor->organizaciones()->attach($request->organizacion_id);*/ //detach() elimina de la lista el usuario que le pasemos 
        

        //Asesor::create($request->all()); // <-- hace todo lo que esta abajo desde new hasta save

//--------------------------------------------------------------------------------------------------------------> comentado

        // EQUIVALENTE --> Asesor::create($request->all()); 

        /*$asesor = new Asesor(); //quiero una nueva instanciade este modelo que va a representar mi tabla (representante de alto nivel)
        $asesor->user_id = Auth::id();
        $asesor->usuario = $request->usuario;
        $asesor->nombre = $request->nombre;
        $asesor->correo = $request->correo; //asignari atributos que corresonden por como se llaman mis columnas
        $asesor->telefono = $request->telefono;
        $asesor->escuela = $request->escuela;
        $asesor->save();*/

//--------------------------------------------------------------------------------------------------------------> comentado

        //Insertar en la tabla usuarios
        /*$usuario = new Usuario(); //quiero una nueva instanciade este modelo que va a representar mi tabla (representante de alto nivel)
        $usuario->usuario = $request->usuario;
        $usuario->mail = $request->correo; //asignari atributos que corresonden por como se llaman mis columnas
        $usuario->pass = $request->pass;
        $usuario->save();*/

        // Notificar por email que el asesor se creo correctamente
        Mail::to($request->user())->send(new NotificaAsesorCreado($asesor));

        return redirect() -> route('asesor.index');
    
        //return redirect('/asesor'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Asesor $asesor)
    {

        // Solo administradores
        if (!Gate::allows('only-admin')) {
            
            if (!Gate::allows('gate-asesor', $asesor)) { // Uso de gate
                return redirect('/asesor');
            }
        }        

        return view('asesor/showAsesor',compact('asesor')); //asesor es el usuario actual a mostrar
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asesor $asesor)
    {
        // Obtener un asesor por su ID
        /*$asesor = Asesor::findOrFail($asesor);

        // Acceder a la columna 'pass' del usuario relacionado
        $password = $asesor->usuario->pass;*/

        //dd($password);

        //$password = $asesor->usuario;

        //return view('asesor/editAsesor',compact('asesor','password')); 


        // Uso de gate
        if (!Gate::allows('gate-asesor', $asesor)) {
            //abort(403);
            return redirect('/asesor');
        }
 

        return view('asesor/editAsesor',compact('asesor')); //formulario para editar la base, asesor es el usuario a editar
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asesor $asesor) ///las reglas del store y el update deben ser las mismas o muy parecidas
    {

        // Uso de gate
        if (!Gate::allows('gate-asesor', $asesor)) {
            return redirect('/asesor');
        }

        $request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            //'nombre' => 'required|string|max:255',
            //'telefono' => ['required','min:10','max:10']
            /*'usuario' => ['required', 'string', 'min:5', 'regex:/^[A-Za-z0-9_-]+$/'],*/
            'nombre' => ['required', 'string', 'min:10', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'correo' => ['required', 'string', 'email', 'min:5', 'max:50'],
            'telefono' => ['nullable','numeric','regex:/^\d{10}$/',],
            
            //'pass' => ['required', 'min:5','max:15', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/']

            //'pass' => ['required', 'min:8','max:15', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/']
            /*La contraseña debe tener al menos 8 caracteres y debe contener al menos una letra minúscula, una letra mayúscula, un número y un carácter especial.*/
        ]);

        /*$asesor -> usuario = $request -> usuario; //Usuario no debe poder modificarse
        $asesor -> nombre = $request -> nombre;
        $asesor -> correo = $request -> correo;
        $asesor -> telefono = $request -> telefono;
        $asesor -> escuela = $request -> escuela;
        $asesor -> save();*/

        //dd($request->except('_token','_method'));

        Asesor::where('id', $asesor->id)->update($request->except('_token','_method')); //opuesto de except (only)

        return redirect() -> route('asesor.show', $asesor); //esto corresponde a el listado de route:list 
        // como estoy mandando a show, necesito mandarle el id del usuario como egundo parametro $asesor <-- este es mi asesor actual
        //return redirect("/asesor/show"); //es lo mismo que esto
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Asesor $asesor)
    {

        /*if ($request->user()->cannot('delete', $asesor)) {
            abort(403);
        }*/


        //Policy
        //$this->authorize('delete', $asesor); //No se podra eliminar ninguno
        
        //DESCOMENTAR para borrar en cascada desde aqui
        /*$asesor->equipos()->delete(); // caso 1 a muchos eliminando los equipos de este asesor*/
        //$asesor->requerimentos()->detach(); // relacion de muchos a muchos

        
        //ELIMINADO (No se necesitó)
        /*$asesor -> delete();
        return redirect('/asesor');*/

        return redirect('/');
    }
}
