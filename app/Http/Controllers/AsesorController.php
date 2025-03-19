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


use App\Models\Competencia;
use App\Models\Institucion;

use App\Mail\NotificaEquipoRegistrado;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use App\Models\Administrador;
use App\Models\Team;
use App\Rules\ValidateUniqueInTables;
use Illuminate\Auth\Events\Registered;
use Laravel\Fortify\Rules\Password;

use GuzzleHttp\Client; // Asegúrate de tener instalado guzzlehttp/guzzle
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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


    /*public function __construct()
    {
        $this->middleware('can:only-user')->except('show');
    }*/

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
        session()->forget('asesor');
        //$orgs = Organizacion::all();
        //return view('asesor/createAsesor', compact('orgs'));
        return view('asesor/createAsesor');
    }

    public function store(Request $request)
    {
        // Validación de campos básicos
        $request->validate([
            //'name'           => 'required|string|max:255',
            //'lastname'       => 'required|string|max:255',
            'email'          => ['required', 'email', new \App\Rules\ValidateUniqueInTables(['users', 'registro_jueces'])],
            'telefono'       => ['nullable', 'numeric', 'unique:users,telefono'],
            //'escuela'        => 'required|string|max:255',
            //'codigo_asesor'  => 'required|string|max:255',
            //'imagen'         => 'required|image|max:2048',
            //'password'       => 'required|string|min:8|confirmed',
        ]);

        // Preparar el cliente Guzzle para enviar la imagen y datos al servidor Flask de IA.
        $client = new Client();
        $imageFile = $request->file('imagen');
        $filePath = $imageFile->getRealPath();

        try {
            // Enviar una solicitud multipart al servidor de IA (Flask)
            $response = $client->request('POST', 'http://localhost:5000/procesar-imagen', [
                'multipart' => [
                    [
                        'name'     => 'imagen',
                        'contents' => fopen($filePath, 'r'),
                        'filename' => $imageFile->getClientOriginalName()
                    ],
                    [
                        'name'     => 'tipoCuenta',
                        'contents' => 'asesor',
                    ],
                    [
                        'name'     => 'name',
                        'contents' => $request->name,
                    ],
                    [
                        'name'     => 'lastname',
                        'contents' => $request->lastname,
                    ],
                    /*[
                        'name'     => 'escuela',
                        'contents' => $request->escuela,
                    ],
                    [
                        'name'     => 'codigo_asesor',
                        'contents' => $request->codigo_asesor,
                    ],*/
                ]
            ]);
        } catch (\Exception $e) {
            // Si ocurre un error al comunicarse con el servicio de IA, se redirige con un mensaje de error
            return redirect()->back()->withErrors(['imagen' => 'Error al procesar la imagen. Inténtalo nuevamente.'])->withInput();
        }

        // Decodificar la respuesta JSON enviada por el servidor de IA.
        $data = json_decode($response->getBody()->getContents(), true);

        /* Se espera que el servidor de IA devuelva un JSON con 4 campos booleanos:
           - nombre_valido
           - apellido_valido
           - escuela_valida
           - codigo_valido
           Estos campos serán true si el texto extraído de la imagen coincide con los datos ingresados.
        */

        if (
            isset($data['nombre_valido'], $data['apellido_valido']/*, $data['escuela_valida'], $data['codigo_valido']*/) &&
            $data['nombre_valido'] === true &&
            $data['apellido_valido'] === true /*&&
            $data['escuela_valida'] === true &&
            $data['codigo_valido'] === true*/
        ) {
            // Si la validación de IA es exitosa, se crea el usuario y se registra al asesor.
            $user = User::create([
                'rol'      => 6, // Rol para asesor
                'name'     => $request->name,
                'lastname' => $request->lastname,
                'email'    => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
            ]);

            $asesor = new Asesor();
            $asesor->user_id = $user->id;
            $asesor->name = $request->name;
            $asesor->lastname = $request->lastname;
            $asesor->email = $request->email;
            $asesor->telefono = $request->telefono;
            //$asesor->escuela       = $request->escuela;
            //$asesor->codigo_asesor = $request->codigo_asesor;

            // Almacenar la imagen en una carpeta definitiva (por ejemplo, public/imagenes)
            /*$imagenPath = $imageFile->store('public/imagenes');
            $asesor->identificacion_path = $imagenPath;*/

            $fileName = Str::slug($request->name, '_');
            $fileLastname = Str::slug($request->lastname, '_');

            $asesor->identificacion_path = $request->file('imagen')->storeAs('public/imagenes_asesores', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());
            $asesor->save();

            $user->sendEmailVerificationNotification();

            return redirect()->route('login')->with('success', 'Asesor registrado correctamente.');
            
        } else {
            // Si alguno de los campos no coincide, redirigir al asesor a una vista de revisión.
            // Se guarda la imagen en una ubicación temporal para mostrarla en la vista.
            $imagenTemporal = $imageFile->store('public/imagenes_temporales');    

            //$fileName = Str::slug($request->name, '_');
            //$fileLastname = Str::slug($request->lastname, '_');
            //$imagenTemporal = $request->file('imagen')->storeAs('public/imagenes_asesores', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());    

            /*return view('asesor/validarCredencial', [
                'dataIA' => $data,               // Resultados de la IA
                'requestData' => $request->all(),     // Datos ingresados por el usuario
                'imagenTemporal'=> $imagenTemporal,      // Ruta de la imagen temporal
            ]);*/

            $asesorRequest = $request->except(['_token', 'imagen']); // Obtiene todos los datos excepto el token CSRF

            session(['asesor.request' => $asesorRequest]); // Guarda los datos en la sesión
            session(['asesor.credencial' => $imagenTemporal]);
            session(['asesor.data' => $data]);

            //dd($data);

            //session()->flash('request', $request);

            //return redirect()->action([self::class, 'validarCredencial']);
            return redirect()->route('asesor.validarcredencial');
        }
    }

    public function validarcredencial(Request $request)
    {
        // Recuperamos los datos almacenados en la sesión 
        //$requestData = session()->get('request');  

        if (session()->has('asesor')){
            $asesorRequest = session('asesor.request', []); // Recupera los datos, si no existen devuelve []
            $imagenTemporal = session('asesor.credencial', []);
            $data = session('asesor.data', []);

            //dd($data);
    
            // Ahora puedes utilizar $datos según lo necesites en este método
            return view('asesor.validarCredencial', compact('asesorRequest', 'imagenTemporal', 'data'));
        }        
        else{
            return redirect()->route('asesor.create');
        }
    }

    public function validarcredencialstore(Request $request)
    {
        // Validación de campos básicos
        $request->validate([
            //'name'           => 'required|string|max:255',
            //'lastname'       => 'required|string|max:255',
            //'email'          => ['required', 'email', new \App\Rules\ValidateUniqueInTables(['users', 'registro_jueces'])],
            //'telefono'       => ['nullable', 'numeric', 'unique:users,telefono'],
            //'escuela'        => 'required|string|max:255',
            //'codigo_asesor'  => 'required|string|max:255',
            //'imagen'         => 'required|image|max:2048',
            //'password'       => 'required|string|min:8|confirmed',
        ]);

        $asesorRequest = session('asesor.request', []); // Recupera los datos, si no existen devuelve []          
        $asesorRequest = (object) $asesorRequest;

        //dd($asesorRequest['name']);
        //dd($asesorRequest->name);
        
        // Preparar el cliente Guzzle para enviar la imagen y datos al servidor Flask de IA.
        $client = new Client();

        if($request->tipo == 'datos'){
            $imagenTemporal = session('asesor.credencial', []);   
            $absolutePath = storage_path('app/' . $imagenTemporal);               
        }
        elseif($request->tipo == 'imagen'){
            $imageFile = $request->file('imagen');
            $filePath = $imageFile->getRealPath();
        }          
        
        //dd($absolutePath);

        try {
            // Enviar una solicitud multipart al servidor de IA (Flask)
            if($request->tipo == 'datos'){
                $response = $client->request('POST', 'http://localhost:5000/procesar-imagen', [
                    'multipart' => [
                        [
                            'name'     => 'imagen',
                            'contents' => fopen($absolutePath, 'r'),
                            'filename' => basename($absolutePath)
                        ],
                        [
                            'name'     => 'tipoCuenta',
                            'contents' => 'asesor',
                        ],
                        [
                            'name'     => 'name',
                            'contents' => $request->name,
                        ],
                        [
                            'name'     => 'lastname',
                            'contents' => $request->lastname,
                        ],
                        /*[
                            'name'     => 'escuela',
                            'contents' => $request->escuela,
                        ],
                        [
                            'name'     => 'codigo_asesor',
                            'contents' => $request->codigo_asesor,
                        ],*/
                    ]
                ]);              
            }
            elseif($request->tipo == 'imagen'){
                $response = $client->request('POST', 'http://localhost:5000/procesar-imagen', [
                    'multipart' => [
                        [
                            'name'     => 'imagen',
                            'contents' => fopen($filePath, 'r'),
                            'filename' => $imageFile->getClientOriginalName()
                        ],
                        [
                            'name'     => 'tipoCuenta',
                            'contents' => 'asesor',
                        ],
                        [
                            'name'     => 'name',
                            'contents' => $asesorRequest->name,
                        ],
                        [
                            'name'     => 'lastname',
                            'contents' => $asesorRequest->lastname,
                        ],
                        /*[
                            'name'     => 'escuela',
                            'contents' => $request->escuela,
                        ],
                        [
                            'name'     => 'codigo_asesor',
                            'contents' => $request->codigo_asesor,
                        ],*/
                    ]
                ]);
            } 
            
        } catch (\Exception $e) {
            // Si ocurre un error al comunicarse con el servicio de IA, se redirige con un mensaje de error
            return redirect()->back()->withErrors(['imagen' => 'Error al procesar la imagen. Inténtalo nuevamente.'])->withInput();
        }

        // Decodificar la respuesta JSON enviada por el servidor de IA.
        $data = json_decode($response->getBody()->getContents(), true);

        /* Se espera que el servidor de IA devuelva un JSON con 4 campos booleanos:
           - nombre_valido
           - apellido_valido
           - escuela_valida
           - codigo_valido
           Estos campos serán true si el texto extraído de la imagen coincide con los datos ingresados.
        */

        if (
            isset($data['nombre_valido'], $data['apellido_valido']/*, $data['escuela_valida'], $data['codigo_valido']*/) &&
            $data['nombre_valido'] === true &&
            $data['apellido_valido'] === true /*&&
            $data['escuela_valida'] === true &&
            $data['codigo_valido'] === true*/
        ) {
            $asesor = new Asesor();

            if($request->tipo == 'datos'){
                // Si la validación de IA es exitosa, se crea el usuario y se registra al asesor.
                $user = User::create([
                    'rol'      => 6, // Rol para asesor
                    'name'     => $request->name,
                    'lastname' => $request->lastname,
                    'email'    => $asesorRequest->email,
                    'telefono' => $asesorRequest->telefono,
                    'password' => Hash::make($asesorRequest->password),
                ]);

                $asesor->name = $request->name;
                $asesor->lastname = $request->lastname;

                $fileName = Str::slug($request->name, '_');
                $fileLastname = Str::slug($request->lastname, '_');

                // Obtener la extensión del archivo desde la ruta temporal
                $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);                

                // Construir el nuevo nombre y la nueva ruta
                $nuevoNombre = 'Identificacion_' . $fileName . '_' . $fileLastname . '.' . $extension;
                $nuevaRuta = 'public/imagenes_asesores/' . $nuevoNombre;

                // Asegurarse de que el directorio de destino exista; si no, crearlo.
                if (!Storage::exists('public/imagenes_asesores')) {
                    Storage::makeDirectory('public/imagenes_asesores');
                }

                // Mover el archivo desde la ruta temporal a la nueva ruta
                // Storage::move() renombra el archivo, trasladándolo y eliminando la versión temporal.
                Storage::move($imagenTemporal, $nuevaRuta);
                //$absolutePath = storage_path('app/' . $imagenTemporal);  

                //dd($nuevaRuta);

                //dd($imagenTemporal);

                // Asignar la nueva ruta al campo del modelo, por ejemplo:
                $asesor->identificacion_path = $nuevaRuta;                
            }
            elseif($request->tipo == 'imagen'){
                // Si la validación de IA es exitosa, se crea el usuario y se registra al asesor.
                $user = User::create([
                    'rol'      => 6, // Rol para asesor
                    'name'     => $asesorRequest->name,
                    'lastname' => $asesorRequest->lastname,
                    'email'    => $asesorRequest->email,
                    'telefono' => $asesorRequest->telefono,
                    'password' => Hash::make($asesorRequest->password),
                ]);

                $asesor->name = $asesorRequest->name;
                $asesor->lastname = $asesorRequest->lastname;

                $fileName = Str::slug($asesorRequest->name, '_');
                $fileLastname = Str::slug($asesorRequest->lastname, '_');

                $asesor->identificacion_path = $request->file('imagen')->storeAs('public/imagenes_asesores', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());
            }
            
            $asesor->user_id = $user->id;            
            $asesor->email = $asesorRequest->email;
            $asesor->telefono = $asesorRequest->telefono;
            //$asesor->escuela       = $request->escuela;
            //$asesor->codigo_asesor = $request->codigo_asesor;

            // Almacenar la imagen en una carpeta definitiva (por ejemplo, public/imagenes)
            /*$imagenPath = $imageFile->store('public/imagenes');
            $asesor->identificacion_path = $imagenPath;*/
            
            $asesor->save();

            $user->sendEmailVerificationNotification();

            session()->forget('asesor');

            return redirect()->route('login')->with('success', 'Asesor registrado correctamente.');
            
        } else {
            // Si alguno de los campos no coincide, redirigir al asesor a una vista de revisión.
            // Se guarda la imagen en una ubicación temporal para mostrarla en la vista.             

            if($request->tipo == 'datos'){                         
                $asesorRequest->name = $request->name;
                $asesorRequest->lastname = $request->lastname;
            }
            elseif($request->tipo == 'imagen'){
                $imagenTemporal = $imageFile->store('public/imagenes_temporales');   
            }  

            //$fileName = Str::slug($request->name, '_');
            //$fileLastname = Str::slug($request->lastname, '_');
            //$imagenTemporal = $request->file('imagen')->storeAs('public/imagenes_asesores', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());    

            /*return view('asesor/validarCredencial', [
                'dataIA' => $data,               // Resultados de la IA
                'requestData' => $request->all(),     // Datos ingresados por el usuario
                'imagenTemporal'=> $imagenTemporal,      // Ruta de la imagen temporal
            ]);*/

            //$asesorRequest = $request->except(['_token', 'imagen']); // Obtiene todos los datos excepto el token CSRF

            $asesorRequest = (array) $asesorRequest;

            session(['asesor.request' => $asesorRequest]); // Guarda los datos en la sesión
            session(['asesor.credencial' => $imagenTemporal]);
            session(['asesor.data' => $data]);

            //dd($data);

            //session()->flash('request', $request);

            //return redirect()->action([self::class, 'validarCredencial']);
            return redirect()->route('asesor.validarcredencial');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function originalStore(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', new ValidateUniqueInTables(['users', 'registro_jueces'])], //| unique:registro_jueces,email",            
            'telefono' => ['nullable','numeric','unique:users,telefono',],
            // Otras reglas de validación para otros campos
        ]);

        /*$request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            //'nombre' => 'required|string|max:255',
            //'telefono' => ['required','min:10','max:10']
            //'usuario' => ['required', 'string', 'min:5', 'regex:/^[A-Za-z0-9_-]+$/'],
            'nombre' => ['required', 'string', 'min:10', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'correo' => ['required', 'string', 'email', 'min:5', 'max:50'],
            'telefono' => ['nullable','numeric','regex:/^\d{10}$/',],
            
            //'pass' => ['required', 'min:5','max:15', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/']

            //'pass' => ['required', 'min:8','max:15', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/']
            //La contraseña debe tener al menos 8 caracteres y debe contener al menos una letra minúscula, una letra mayúscula, un número y un carácter especial.
        ]);*/
    
        
        
        //Contacto --> a las clases se les nombra con matusculas (modelos)        
        //$usuario->comentario = $request->comentario; 
        //$asesor->pass = $request->pass;
        
        

        //Forma nueva

        // Insertar un dato en el request
        //$request->merge(['user_id' => Auth::id()]); //Inyectar el user id en el request

       
        //$asesor = Asesor::create($request->only('id'));

        //dd($request->organizacion_id); //PRUEBA DD

        
        //$asesor = Asesor::create($request->all()); // <-- hace todo lo que esta abajo desde new hasta save


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
        //Mail::to($request->user())->send(new NotificaAsesorCreado($asesor));


//--------------------------------------------------------------------------------------------------------------> Nuevo

        $user = User::create([
            'rol' => 6,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),                
        ]);
        
        //$this->createTeam($user);

        //$user->sendEmailVerificationNotification();


        // EQUIVALENTE --> Asesor::create($request->all()); 

        $asesor = new Asesor(); //quiero una nueva instancia de este modelo que va a representar mi tabla (representante de alto nivel)
        $asesor->user_id = $user->id;
        $asesor->name = $request->name;
        $asesor->lastname = $request->lastname; //asignar atributos que corresonden por como se llaman mis columnas
        $asesor->email = $request->email;      
        $asesor->telefono = $request->telefono;
        $asesor->save();

        // Enviar automáticamente el correo de verificación
        //event(new Registered($user));
        $user->sendEmailVerificationNotification();

        // Redirigir con un mensaje de éxito
        return redirect()->route('login');
        //->with('success', 'Registro completado. Por favor verifica tu correo electrónico.');
        
        //return redirect() -> route('asesor.index');
    
        //return redirect('/asesor'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Asesor $asesor)
    {

        // Solo administradores
        if (!Gate::allows('only-superadmin')) {
            
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
