<?php

namespace App\Http\Controllers;

use App\Mail\NotificaAsesorCreado;
use App\Mail\NotificaCuentaAsesorAprobada;
use App\Mail\NotificaCuentaAsesorPendiente;
use App\Mail\NotificaCuentaAsesorRechazada;
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
use App\Models\AsesorInstitucionSolicitud;
use App\Models\Team;
use App\Rules\ValidateUniqueInTables;
use Illuminate\Auth\Events\Registered;
use Laravel\Fortify\Rules\Password;

use GuzzleHttp\Client; // Asegúrate de tener instalado guzzlehttp/guzzle
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

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

    // Usuario
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


            //============================================================================>

            // Crear link para verificacion de correo
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify', // Nombre de la ruta de verificación
                now()->addMinutes(60),  // Tiempo de expiración del enlace
                ['id' => $user->id, 'hash' => sha1($user->email)] // Parámetros de la ruta
            );

            //dd($verificationUrl);

            // Enviar correo de activacion de cuenta
            Mail::to($asesor->email)->send(new NotificaCuentaAsesorAprobada($asesor, $verificationUrl));
            
            //============================================================================>

            $user->sendEmailVerificationNotification();

            //return redirect()->route('login')->with('success', 'Asesor registrado correctamente.');
            return redirect()->route('login')->with('success', '<b style="color: #41ef1f;">Cuenta de Asesor registrada correctamente.</b> <br> <i>Antes de continuar, por favor verifique su dirección de correo electrónico.</i>');
            
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

    // Usuario
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

    // Usuario
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


            //============================================================================>
            
            // Crear link para verificacion de correo
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify', // Nombre de la ruta de verificación
                now()->addMinutes(60),  // Tiempo de expiración del enlace
                ['id' => $user->id, 'hash' => sha1($user->email)] // Parámetros de la ruta
            );

            //dd($verificationUrl);

            // Enviar correo de activacion de cuenta
            Mail::to($asesor->email)->send(new NotificaCuentaAsesorAprobada($asesor, $verificationUrl));
            
            //============================================================================>

            $user->sendEmailVerificationNotification();

            session()->forget('asesor');

            //return redirect()->route('login')->with('success', 'Asesor registrado correctamente.');
            return redirect()->route('login')->with('success', '<b style="color: #41ef1f;">Cuenta de Asesor registrada correctamente.</b> <br> <i>Antes de continuar, por favor verifique su dirección de correo electrónico.</i>');
            
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


    // Usuario
    public function revisarcredencialmanualmente()
    {
        // Validación de campos básicos
        /*$request->validate([
            //'name'           => 'required|string|max:255',
            //'lastname'       => 'required|string|max:255',
            'email'          => ['required', 'email', new \App\Rules\ValidateUniqueInTables(['users', 'registro_jueces'])],
            'telefono'       => ['nullable', 'numeric', 'unique:users,telefono'],
            //'escuela'        => 'required|string|max:255',
            //'codigo_asesor'  => 'required|string|max:255',
            //'imagen'         => 'required|image|max:2048',
            //'password'       => 'required|string|min:8|confirmed',
        ]);*/
        
        $asesorRequest = session('asesor.request', []); // Recupera los datos, si no existen devuelve []          
        $asesorRequest = (object) $asesorRequest;

        $imagenTemporal = session('asesor.credencial', []);   
        $absolutePath = storage_path('app/' . $imagenTemporal);   


        $user = User::create([
            'rol'      => 6, // Rol para asesor
            'name'     => $asesorRequest->name,
            'lastname' => $asesorRequest->lastname,
            'email'    => $asesorRequest->email,
            'telefono' => $asesorRequest->telefono,
            'password' => Hash::make($asesorRequest->password),
        ]);

        $asesor = new Asesor();

        $asesor->user_id = $user->id;
        $asesor->name = $asesorRequest->name;
        $asesor->lastname = $asesorRequest->lastname;
        
        $asesor->email = $asesorRequest->email;
        $asesor->telefono = $asesorRequest->telefono;

        $fileName = Str::slug($asesorRequest->name, '_');
        $fileLastname = Str::slug($asesorRequest->lastname, '_');

        //$asesor->identificacion_path = $request->file('imagen')->storeAs('public/imagenes_asesores', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());


        // Obtener la extensión del archivo desde la ruta temporal
        $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);                

        // Construir el nuevo nombre y la nueva ruta
        $nuevoNombre = 'Identificacion_' . $fileName . '_' . $fileLastname . '.' . $extension;
        $nuevaRuta = 'public/imagenes_asesores_pendientes/' . $nuevoNombre;

        // Asegurarse de que el directorio de destino exista; si no, crearlo.
        if (!Storage::exists('public/imagenes_asesores_pendientes')) {
            Storage::makeDirectory('public/imagenes_asesores_pendientes');
        }

        // Mover el archivo desde la ruta temporal a la nueva ruta
        // Storage::move() renombra el archivo, trasladándolo y eliminando la versión temporal.
        Storage::move($imagenTemporal, $nuevaRuta);
        //$absolutePath = storage_path('app/' . $imagenTemporal);  

        //dd($nuevaRuta);

        //dd($imagenTemporal);

        // Asignar la nueva ruta al campo del modelo, por ejemplo:
        $asesor->identificacion_path = $nuevaRuta;       
        
        $asesor->verificada = false;  
        $asesor->observaciones = false;        
        $asesor->save();

        //$user->sendEmailVerificationNotification();   
        Mail::to($asesor->email)->send(new NotificaCuentaAsesorPendiente($asesor));
        
        $user->delete();        

        session()->forget('asesor');
        
        /*$user = User::create([
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
        //$imagenPath = $imageFile->store('public/imagenes');
        //$asesor->identificacion_path = $imagenPath;

        $fileName = Str::slug($request->name, '_');
        $fileLastname = Str::slug($request->lastname, '_');

        $asesor->identificacion_path = $request->file('imagen')->storeAs('public/imagenes_asesores_pendientes', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());
        //$imagenTemporal = $imageFile->store('public/imagenes_temporales');    

        $asesor->verificada = false;        
        $asesor->save();

        //$user->sendEmailVerificationNotification();   
        Mail::to($asesor->email)->send(new NotificaCuentaAsesorPendiente($asesor));
        
        $user->delete();*/

        // Configura los datos para la notificación
        session()->flash('alerta', [
            'titulo' => '"Cuenta de Asesor enviada para su aprobación"',
            'texto' => 'En los próximos días te notificaremos por correo si tu información fue validada exitosamente.',
            'icono' => 'info',
            //'tiempo' => 2500,
            'botonConfirmacion' => true,
            'colorBoton' => '#3085d6',
        ]);

        return redirect('/');
        
        //return redirect()->route('asesor.validarcredencial');

    }

    public function pruebaSweet(){
        // Configura los datos para la notificación
        session()->flash('alerta', [
            'titulo' => '"Cuenta de Asesor enviada para su aprobación"',
            'texto' => 'En los próximos días te notificaremos por correo si tu información fue validada exitosamente.',
            'icono' => 'info',
            //'tiempo' => 2500,
            'botonConfirmacion' => true,
        ]);

        return redirect('/');
    }

    // Administrador view
    public function validarcuenta()
    {
        if (auth()->check()) { // Verifica si el usuario está logueado
            
            $user = auth()->user();

            if ($user->rol == 1 || $user->rol == 2) {

                $cuentasAsesores = Asesor::where('verificada', 0)
                //->where('observaciones', '!=', 1) // Asegura que observaciones no sea 1
                ->where('observaciones', 0)
                ->orderBy('name', 'asc')
                ->get();


                $cuentasAsesorescount = $cuentasAsesores->count();

                return view("asesor/validarCuentaAsesor",compact('cuentasAsesores', 'cuentasAsesorescount')); 

            } else{
                return redirect('/');
            }
        }
        else{
            return redirect('/');
        }                      
    }

    // Administrador view
    public function showvalidarcuenta(Asesor $asesor)
    {
        $cuentasAsesores = Asesor::where('verificada', 0)
        //->where('observaciones', '!=', 1) // Asegura que observaciones no sea 1
        ->where('observaciones', 0)
        ->orderBy('name', 'asc')
        ->get();

        // Encontrar el índice del asesor actual en la colección
        $indiceAsesorActual = $cuentasAsesores->search(function ($item) use ($asesor) {
            return $item->id === $asesor->id;
        });

        // Obtener el asesor anterior, si existe
        $asesorAnterior = $indiceAsesorActual > 0 ? $cuentasAsesores[$indiceAsesorActual - 1] : null;
        // Obtener el asesor siguiente, si existe
        $asesorSiguiente = $indiceAsesorActual < $cuentasAsesores->count() - 1 ? $cuentasAsesores[$indiceAsesorActual + 1] : null;

        //dd($asesorAnterior);
        //dd($asesorSiguiente);

        return view('asesor/showValidarCuentaAsesor',compact('asesor', 'asesorAnterior', 'asesorSiguiente')); //asesor es el usuario actual a mostrar
    }

    // Administrador
    public function aprobarcuenta(Request $request, Asesor $asesor)
    {        

        //dd($request->asesor_anterior_id);
        //dd($request->asesor_siguiente_id);           

        // Recuperar cuenta de usuario (Busca el registro eliminado por ID)
        $user = User::onlyTrashed()->findOrFail($asesor->user_id); // Busca solo registros eliminados
    
        // Restaura el registro
        $user->restore();            

        // Activar cuenta de asesor
        $asesor->verificada = true;        

        // Eliminar dato de observaciones (NULL)
        $asesor->observaciones = null;

        $asesor->save();

        // Crear link para verificacion de correo
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', // Nombre de la ruta de verificación
            now()->addMinutes(60),  // Tiempo de expiración del enlace
            ['id' => $user->id, 'hash' => sha1($user->email)] // Parámetros de la ruta
        );

        //dd($verificationUrl);

        // Enviar correo de activacion de cuenta
        Mail::to($asesor->email)->send(new NotificaCuentaAsesorAprobada($asesor, $verificationUrl));

        $user->sendEmailVerificationNotification();

        // Configura los datos para la notificación
        session()->flash('alerta', [
            'titulo' => '"Cuenta de Asesor aprobada exitosamente"',            
            //'texto' => 'La cuenta de ' . $asesor->email . ' fue activada exitosamente.',
            'html' => 'La cuenta de <b><i>' . $asesor->email .  '</i></b> fue activada exitosamente.',
            'icono' => 'success',
            'tiempo' => 3000,
            'botonConfirmacion' => false,            
        ]);


        //Asesor anterior
        if($request->asesor_anterior_id){
            $asesorAnterior = Asesor::findOrFail($request->asesor_anterior_id);
        }else{
            $asesorAnterior = null;
        }

        //Asesor siguiente
        if($request->asesor_siguiente_id){
            $asesorSiguiente = Asesor::findOrFail($request->asesor_siguiente_id);
        }else{
            $asesorSiguiente = null;
        }

        //dd($asesorAnterior);
        //dd($asesorSiguiente);

        if($asesorSiguiente){
            return redirect() -> route('asesor.showvalidarcuenta', $asesorSiguiente);
        }
        elseif($asesorAnterior){
            return redirect() -> route('asesor.showvalidarcuenta', $asesorAnterior);
        }
        else{
            return redirect('/asesor/validarcuenta');
        }        

        //return redirect('/administrador/trashed');
    }

    // Administrador
    public function rechazarcuenta(Request $request, Asesor $asesor)
    {
        // Validar la entrada
        $data = $request->validate([
            'observaciones' => 'required|string'
        ]);

        //dd($request->all());


        // Activar casilla de observaciones        
        $asesor->observaciones = true;        
        
        
        // Generar código único de rechazo
        $asesor->codigo_rechazo = $this->generateUniqueCode();   
        
        //dd($asesor->codigo_rechazo);

        $asesor->save();

        $observaciones = $request->observaciones;
        
        // CREAR VISTA DE CORRECCION DE DATOS POR MEDIO DE URL
        // volver a revisar los datos y volver a enviarlo a revision manual (bucle)

        // Enviar correo de rechazo de cuenta
        Mail::to($asesor->email)->send(new NotificaCuentaAsesorRechazada($asesor, $observaciones));        

        // Configura los datos para la notificación
        session()->flash('alerta', [
            'titulo' => '"Cuenta de Asesor rechzada correctamente"',
            //'texto' => 'La cuenta de ' . $asesor->email . ' fue activada exitosamente.',
            'html' => 'La cuenta de <b><i>' . $asesor->email .  '</i></b> fue rechazada y devuelta para su revisión.',
            'icono' => 'success',
            'tiempo' => 3000,
            'botonConfirmacion' => false,            
        ]);


        //Asesor anterior
        if($request->asesor_anterior_id){
            $asesorAnterior = Asesor::findOrFail($request->asesor_anterior_id);
        }else{
            $asesorAnterior = null;
        }

        //Asesor siguiente
        if($request->asesor_siguiente_id){
            $asesorSiguiente = Asesor::findOrFail($request->asesor_siguiente_id);
        }else{
            $asesorSiguiente = null;
        }

        //dd($asesorAnterior);
        //dd($asesorSiguiente);

        if($asesorSiguiente){
            return redirect() -> route('asesor.showvalidarcuenta', $asesorSiguiente);
        }
        elseif($asesorAnterior){
            return redirect() -> route('asesor.showvalidarcuenta', $asesorAnterior);
        }
        else{
            return redirect('/asesor/validarcuenta');
        }

        //dd($observaciones);

        //Enviar correo de rechazo de cuenta

        //

        // Lógica para rechazar la cuenta y enviar notificaciones
        // Por ejemplo, actualizar el estado del asesor, guardar las observaciones, etc.
        //$asesor->observaciones = $data['observaciones'];
        //$asesor->estado = 'rechazado';
        //$asesor->save();

        // Redirigir al usuario a otra ruta, por ejemplo, a una página de confirmación
        //return redirect()->route('asesor.dashboard')->with('success', 'Cuenta rechazada y observaciones enviadas.');
    }

    /**
     * Genera un código único que no exista en la base de datos.
     */
    private function generateUniqueCode($length = 13)
    {
        do {
            $codigo = "AR" . Str::random($length); // Genera un código aleatorio alfanumérico
        } while (Asesor::where('codigo_rechazo', $codigo)->exists()); // Verifica si el código ya existe

        return $codigo;
    }

    // Usuario view
    public function buscarcuenta()
    {
        if (Auth::check()) {
            // Si el usuario está autenticado, redirigirlo a la página de inicio
            return redirect('/');
        }
    
        // Si no está autenticado, mostrar la vista        
        return view("asesor/buscarCuentaAsesor"); 
    }

    // Usuario
    public function buscarcuentastore(Request $request)
    {
        // Validar los campos
        $request->validate([            
            //'codigo_rechazo' => 'required|min:15|max:15|exists:asesores,codigo_rechazo',            
            'codigo_rechazo' => 'required|min:15|max:15',            
        ]);

        //dd($request->codigo_rechazo);

        $codigo = $request->codigo_rechazo;

        // Buscar el código de registro en la base de datos
        $asesor = Asesor::where('codigo_rechazo', $codigo)->first();

        //dd($asesor);

        if($asesor){                    
            return redirect() -> route('asesor.validarcredencialrechazada', $asesor->codigo_rechazo);
        }
        else{
            // Configura los datos para la notificación
            session()->flash('alerta', [
                'titulo' => '"Código de reporte no valido"',            
                'texto' => 'Por favor ingrese un codigo de reporte valido para modificar su cuenta. Revise nuevamente el correo que le notificó si tiene alguna duda.',
                'icono' => 'error',
                //'tiempo' => 5000,
                'botonConfirmacion' => true,            
            ]);

            //dd($request->all());

            return redirect() -> route('asesor.buscarcuenta');
        }
    }

    // Usuario view (New)
    public function validarcredencialrechazada($codigo_rechazo)
    {

        // Buscar el código de registro en la base de datos
        $asesor = Asesor::where('codigo_rechazo', $codigo_rechazo)->first();  

        //dd($asesor);

        // Reemplaza 'public/' con 'storage/' para obtener la ruta completa del archivo
        $rutaArchivo = public_path('storage/' . str_replace('public/', '', $asesor->identificacion_path)); // Ruta completa del archivo almacenado

        //dd($rutaArchivo);
                

        // Convertir imagen como si fuera upload
        if (file_exists($rutaArchivo)) {
            // Simular que la imagen es un archivo subido
            $imageFile = new UploadedFile(
                $rutaArchivo,                // Ruta real del archivo
                basename($rutaArchivo),       // Nombre del archivo
                mime_content_type($rutaArchivo), // Tipo MIME
                null,                         // Error (dejar null)
                true                          // Indica que es un archivo real del sistema
            );

            // Obtener la ruta real
            $filePath = $imageFile->getRealPath();

            // Ahora puedes usar $imageFile como si fuera un archivo subido
        }
        
        // Preparar el cliente Guzzle para enviar la imagen y datos al servidor Flask de IA.
        $client = new Client();        
        

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
                        'contents' => $asesor->name,
                    ],
                    [
                        'name'     => 'lastname',
                        'contents' => $asesor->lastname,
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

        //$asesorRequest = $asesor->only(['name', 'lastname']);

        /*$asesorRequest = array_merge(
            $asesor->only(['name', 'lastname']),            
        );*/

        //session(['asesor.request' => $asesorRequest]); // Guarda los datos en la sesión
        session(['asesor.credencial' => $imagenTemporal]);
        session(['asesor.data' => $data]);

        session(['asesor.id' => $asesor->id]);
        session(['asesor.rutaOriginalImagen' => $rutaArchivo]);
        session(['asesor.codigo_rechazo' => $asesor->codigo_rechazo]);

        //dd($data);

        //session()->flash('request', $request);

        //return redirect()->action([self::class, 'validarCredencial']);
        //return redirect()->route('asesor.validarcredencial');        



        // Recuperamos los datos almacenados en la sesión 
        //$requestData = session()->get('request');  

    
        if (session()->has('asesor')){
            //$asesorRequest = session('asesor.request', []); // Recupera los datos, si no existen devuelve []

            $asesor_id = session('asesor.id', []);
            $asesor = Asesor::where('id', $asesor_id)->first();

            $imagenTemporal = session('asesor.credencial', []);
            $data = session('asesor.data', []);

            $codigo_rechazo = session('asesor.codigo_rechazo', []);

            //dd($data);
    
            $primeraRevision = true;

            // Ahora puedes utilizar $datos según lo necesites en este método
            return view('asesor.validarCredencialRechazada', compact('asesor', 'imagenTemporal', 'data', 'primeraRevision', 'codigo_rechazo'));
        }        
        else{
            return redirect('/');
        }

        // Retornar la vista del formulario con el código
        //return view('asesor/validarCredencialRechazada', compact('asesor'));        
    }


    // Usuario (New)
    public function validarcredencialrechazadastore(Request $request)
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

        /*$asesorRequest = session('asesor.request', []); // Recupera los datos, si no existen devuelve []          
        $asesorRequest = (object) $asesorRequest;*/

        $asesor_id = session('asesor.id', []);
        $asesor = Asesor::where('id', $asesor_id)->first();
        
        $rutaOriginalImagen = session('asesor.rutaOriginalImagen', []);        
        $codigo_rechazo = session('asesor.codigo_rechazo', []);

        //dd($asesorRequest);                 


        //dd($asesorRequest['name']);
        //dd($asesorRequest->name);
        
        // Preparar el cliente Guzzle para enviar la imagen y datos al servidor Flask de IA.
        $client = new Client();

        
        $imagenTemporal = session('asesor.credencial', []);   
        $absolutePath = storage_path('app/' . $imagenTemporal);

        //dd($absolutePath);
        
        if($request->tipo == 'imagen'){
            $imageFile = $request->file('imagen');
            $filePath = $imageFile->getRealPath();
        }

        /*if($request->tipo == 'datos'){
            $imagenTemporal = session('asesor.credencial', []);   
            $absolutePath = storage_path('app/' . $imagenTemporal);

            //dd($absolutePath);
        }
        elseif($request->tipo == 'imagen'){
            $imageFile = $request->file('imagen');
            $filePath = $imageFile->getRealPath();
        }*/          
        
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
                            'contents' => $asesor->name,
                        ],
                        [
                            'name'     => 'lastname',
                            'contents' => $asesor->lastname,
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

            if($request->tipo == 'datos'){

                //$asesor = Asesor::where('id', $asesor_id)->first();

                //dd($asesor);

                // Recuperar cuenta de usuario (Busca el registro eliminado por ID)
                $user = User::onlyTrashed()->findOrFail($asesor->user_id); // Busca solo registros eliminados                
            
                // Restaura el registro
                $user->restore();         
                
                $user->name = $request->name;  

                $user->lastname = $request->lastname;

                $user->save();


                $asesor->name = $request->name;

                $asesor->lastname = $request->lastname;

                // Activar cuenta de asesor
                $asesor->verificada = true;        

                // Eliminar dato de observaciones (NULL)
                $asesor->observaciones = null;

                $asesor->codigo_rechazo = null;                
                

                $fileName = Str::slug($request->name, '_');
                $fileLastname = Str::slug($request->lastname, '_');

                // Obtener la extensión del archivo desde la ruta temporal
                $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);                

                // Construir el nuevo nombre y la nueva ruta
                $nuevoNombre = 'Identificacion_' . $fileName . '_' . $fileLastname . '.' . $extension;
                $nuevaRuta = 'public/imagenes_asesores/' . $nuevoNombre;

                // Asignar la nueva ruta al campo del modelo, por ejemplo:
                $asesor->identificacion_path = $nuevaRuta;   

                // Asegurarse de que el directorio de destino exista; si no, crearlo.
                if (!Storage::exists('public/imagenes_asesores')) {
                    Storage::makeDirectory('public/imagenes_asesores');
                }

                // Mover el archivo desde la ruta temporal a la nueva ruta
                // Storage::move() renombra el archivo, trasladándolo y eliminando la versión temporal.
                Storage::move($imagenTemporal, $nuevaRuta);

                //Eliminar imagen original almacenada en "imagenes_asesores_pendientes"
                if (file_exists($rutaOriginalImagen)) {
                    unlink($rutaOriginalImagen); // Eliminar el archivo            
                }  

                //Eliminar imagen temporal upload almacenada en "imagenes_temporales"
                if (file_exists($absolutePath)) {
                    unlink($absolutePath); // Elimina el archivo
                } 

                //$absolutePath = storage_path('app/' . $imagenTemporal);  

                //dd($nuevaRuta);

                //dd($imagenTemporal);                              
                                        
            }
            elseif($request->tipo == 'imagen'){

                //$asesor = Asesor::where('id', $asesor_id)->first();

                // Recuperar cuenta de usuario (Busca el registro eliminado por ID)
                $user = User::onlyTrashed()->findOrFail($asesor->user_id); // Busca solo registros eliminados                
            
                // Restaura el registro
                $user->restore();

                // Activar cuenta de asesor
                $asesor->verificada = true;        

                // Eliminar dato de observaciones (NULL)
                $asesor->observaciones = null;

                $asesor->codigo_rechazo = null;                


                $fileName = Str::slug($asesor->name, '_');
                $fileLastname = Str::slug($asesor->lastname, '_');

                $asesor->identificacion_path = $request->file('imagen')->storeAs('public/imagenes_asesores', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());                
                                

                //Eliminar imagen original almacenada en "imagenes_asesores_pendientes"
                if (file_exists($rutaOriginalImagen)) {
                    unlink($rutaOriginalImagen); // Eliminar el archivo            
                }  

                //Eliminar imagen temporal upload almacenada en "imagenes_temporales"
                if (file_exists($absolutePath)) {
                    unlink($absolutePath); // Elimina el archivo
                }
            }      
            
            $asesor->save();


            //============================================================================>
            
            // Crear link para verificacion de correo
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify', // Nombre de la ruta de verificación
                now()->addMinutes(60),  // Tiempo de expiración del enlace
                ['id' => $user->id, 'hash' => sha1($user->email)] // Parámetros de la ruta
            );

            //dd($verificationUrl);

            // Enviar correo de activacion de cuenta
            Mail::to($asesor->email)->send(new NotificaCuentaAsesorAprobada($asesor, $verificationUrl));
            
            //============================================================================>

            $user->sendEmailVerificationNotification();

            session()->forget('asesor');

            //return redirect()->route('login')->with('success', 'Asesor registrado correctamente.');
            return redirect()->route('login')->with('success', '<b style="color: #41ef1f;">Cuenta de Asesor registrada correctamente.</b> <br> <i>Antes de continuar, por favor verifique su dirección de correo electrónico.</i>');
            
        } else {
            // Si alguno de los campos no coincide, redirigir al asesor a una vista de revisión.
            // Se guarda la imagen en una ubicación temporal para mostrarla en la vista.             

            //dd($asesorRequest->id);
            //$asesor = Asesor::where('id', $asesor_id)->first();
            
            if($request->tipo == 'datos'){                         
                //$asesorRequest->name = $request->name;
                //$asesorRequest->lastname = $request->lastname;
                

                $user = User::onlyTrashed()->findOrFail($asesor->user_id); // Busca solo registros eliminados                
                                                     
                $user->name = $request->name;  
                $user->lastname = $request->lastname;
                $user->save();

                $asesor->name = $request->name;
                $asesor->lastname = $request->lastname;
                $asesor->save();

            }
            elseif($request->tipo == 'imagen'){
                $imagenTemporal = $imageFile->store('public/imagenes_asesores_pendientes');                                 
                $absolutePath = storage_path('app/' . $imagenTemporal); 


                //==================================================================>

                // Convierte la ruta absoluta en una ruta relativa
                $relativePath = str_replace(storage_path('app/'), '', $absolutePath);

                //dd($relativePath);

                $asesor->identificacion_path = $relativePath;  
                $asesor->save();
                
                //Eliminar imagen original almacenada en "imagenes_asesores_pendientes"
                if (file_exists($rutaOriginalImagen)) {
                    unlink($rutaOriginalImagen); // Eliminar el archivo            
                }

                session(['asesor.rutaOriginalImagen' => $relativePath]);

                //==================================================================>
            }  

            //dd($asesorRequest);

            //$fileName = Str::slug($request->name, '_');
            //$fileLastname = Str::slug($request->lastname, '_');
            //$imagenTemporal = $request->file('imagen')->storeAs('public/imagenes_asesores', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());    

            /*return view('asesor/validarCredencial', [
                'dataIA' => $data,               // Resultados de la IA
                'requestData' => $request->all(),     // Datos ingresados por el usuario
                'imagenTemporal'=> $imagenTemporal,      // Ruta de la imagen temporal
            ]);*/

            //$asesorRequest = $request->except(['_token', 'imagen']); // Obtiene todos los datos excepto el token CSRF

            //$asesorRequest = (array) $asesorRequest;

            //dd($asesorRequest);

            //dd($asesorRequest);
            //dd($asesorRequest['telefono']);

            //session(['asesor.request' => $asesorRequest]); // Guarda los datos en la sesión
            session(['asesor.credencial' => $imagenTemporal]);
            session(['asesor.data' => $data]);

            //dd($data);

            //session()->flash('request', $request);

            //return redirect()->action([self::class, 'validarCredencial']);
            //return redirect()->route('asesor.validarcredencial');

            if (session()->has('asesor')){
                //$asesorRequest = session('asesor.request', []); // Recupera los datos, si no existen devuelve []
    
                $asesor_id = session('asesor.id', []);
                $asesor = Asesor::where('id', $asesor_id)->first();
    
                $imagenTemporal = session('asesor.credencial', []);
                $data = session('asesor.data', []);
    
                $codigo_rechazo = session('asesor.codigo_rechazo', []);
    
                //dd($data);
        
                $primeraRevision = false;
    
                // Ahora puedes utilizar $datos según lo necesites en este método
                return view('asesor.validarCredencialRechazada', compact('asesor', 'imagenTemporal', 'data', 'primeraRevision', 'codigo_rechazo'));
            }        
            else{
                return redirect('/');
            }
        }
    }


    // Usuario (New)
    public function revisarcredencialrechazadamanualmente()
    {
        // Validación de campos básicos
        /*$request->validate([
            //'name'           => 'required|string|max:255',
            //'lastname'       => 'required|string|max:255',
            'email'          => ['required', 'email', new \App\Rules\ValidateUniqueInTables(['users', 'registro_jueces'])],
            'telefono'       => ['nullable', 'numeric', 'unique:users,telefono'],
            //'escuela'        => 'required|string|max:255',
            //'codigo_asesor'  => 'required|string|max:255',
            //'imagen'         => 'required|image|max:2048',
            //'password'       => 'required|string|min:8|confirmed',
        ]);*/
        
        //$asesorRequest = session('asesor.request', []); // Recupera los datos, si no existen devuelve []          
        //$asesorRequest = (object) $asesorRequest;        

        $asesor_id = session('asesor.id', []);
        $asesor = Asesor::where('id', $asesor_id)->first();
        
        $rutaOriginalImagen = session('asesor.rutaOriginalImagen', []);

        $imagenTemporal = session('asesor.credencial', []);   
        $absolutePath = storage_path('app/' . $imagenTemporal);   

        //dd($absolutePath);           

        $fileName = Str::slug($asesor->name, '_');
        $fileLastname = Str::slug($asesor->lastname, '_');

        //$asesor->identificacion_path = $request->file('imagen')->storeAs('public/imagenes_asesores', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());


        // Obtener la extensión del archivo desde la ruta temporal
        $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);                

        // Construir el nuevo nombre y la nueva ruta
        $nuevoNombre = 'Identificacion_' . $fileName . '_' . $fileLastname . '.' . $extension;
        $nuevaRuta = 'public/imagenes_asesores_pendientes/' . $nuevoNombre;

        // Asignar la nueva ruta al campo del modelo, por ejemplo:
        $asesor->identificacion_path = $nuevaRuta;    


        // Asegurarse de que el directorio de destino exista; si no, crearlo.
        if (!Storage::exists('public/imagenes_asesores_pendientes')) {
            Storage::makeDirectory('public/imagenes_asesores_pendientes');
        }

        // Mover el archivo desde la ruta temporal a la nueva ruta
        // Storage::move() renombra el archivo, trasladándolo y eliminando la versión temporal.
        Storage::move($imagenTemporal, $nuevaRuta);
        //$absolutePath = storage_path('app/' . $imagenTemporal);  


        //Eliminar imagen original almacenada en "imagenes_asesores_pendientes"
        if (file_exists($rutaOriginalImagen)) {
            unlink($rutaOriginalImagen); // Eliminar el archivo            
        }  

        //Eliminar imagen temporal upload almacenada en "imagenes_temporales"
        if (file_exists($absolutePath)) {
            unlink($absolutePath); // Elimina el archivo
        }

        //dd($nuevaRuta);

        //dd($imagenTemporal);

        
        // Eliminar dato de observaciones (NULL)
        $asesor->observaciones = false;
        $asesor->codigo_rechazo = null;      
        $asesor->save();

        //$user->sendEmailVerificationNotification();   
        Mail::to($asesor->email)->send(new NotificaCuentaAsesorPendiente($asesor));                    

        session()->forget('asesor');
        
        /*$user = User::create([
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
        //$imagenPath = $imageFile->store('public/imagenes');
        //$asesor->identificacion_path = $imagenPath;

        $fileName = Str::slug($request->name, '_');
        $fileLastname = Str::slug($request->lastname, '_');

        $asesor->identificacion_path = $request->file('imagen')->storeAs('public/imagenes_asesores_pendientes', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagen')->extension());
        //$imagenTemporal = $imageFile->store('public/imagenes_temporales');    

        $asesor->verificada = false;        
        $asesor->save();

        //$user->sendEmailVerificationNotification();   
        Mail::to($asesor->email)->send(new NotificaCuentaAsesorPendiente($asesor));
        
        $user->delete();*/

        // Configura los datos para la notificación
        session()->flash('alerta', [
            'titulo' => '"Cuenta de Asesor enviada para su aprobación"',
            'texto' => 'En los próximos días te notificaremos por correo si tu información fue validada exitosamente.',
            'icono' => 'info',
            //'tiempo' => 2500,
            'botonConfirmacion' => true,
            'colorBoton' => '#3085d6',
        ]);

        return redirect('/');
        
        //return redirect()->route('asesor.validarcredencial');

    }


    //=========================================================================================================================>

    public function perfil()
    {        
        $asesor = auth()->user()->asesor;

        //dd($institucion->user);

        return view('asesor/perfilasesor',compact('asesor')); 
    }

    public function ocultarContacto()
    {
        //$user = auth()->user(); // Obtiene el usuario autenticado
        $asesor = auth()->user()->asesor; // Relación User -> Institucion

        if ($asesor->contacto_oculto == true) {
            $asesor->contacto_oculto = false;
            $asesor->save();

            return;
        }
        else{
            $asesor->contacto_oculto = true;
            $asesor->save();

            return;
        }

        //return response()->json(['message' => 'Error al actualizar'], 400);
    }

    public function actualizarCredencial(Request $request)
    {
        //$user = auth()->user(); // Obtiene el usuario autenticado
        $asesor = auth()->user()->asesor; // Relación User -> Institucion
        
        $fileName = Str::slug($asesor->name, '_');
        $fileLastname = Str::slug($asesor->lastname, '_');
        $asesor->identificacion_path = $request->file('imagenCredencial')->storeAs('public/imagenes_asesores', 'Identificacion_'.$fileName.'_'.$fileLastname.'.'. $request->file('imagenCredencial')->extension());
        
        $asesor->save();
        
        //return redirect()->route('institucion.perfil');
        return redirect($request->ruta);

        //return response()->json(['message' => 'Error al actualizar'], 400);
    }
    
    public function actualizarImagenPerfil(Request $request)
    {
        $user = User::find(Auth::user()->id); // Obtiene el usuario autenticado        
        $request->file('imagenPerfil')->storeAs('public/profile-photos', 'imagenPerfil_'.$user->name.'.'. $request->file('imagenPerfil')->extension());
        $user->profile_photo_path = 'profile-photos/imagenPerfil_'.$user->name.'.'. $request->file('imagenPerfil')->extension();        
        $user->save();
        
        //return redirect()->route('institucion.perfil');
        return redirect($request->ruta);

        //return response()->json(['message' => 'Error al actualizar'], 400);
    }    

    /**
     * Show the form for editing the specified resource.
     */
    public function perfiledit()
    {
        $asesor = auth()->user()->asesor;

        //dd($asesor->user);

        return view('asesor/editPerfilAsesor',compact('asesor')); 
    }
    
    public function eliminarImagenPerfil(Request $request)
    {
        $user = User::find(Auth::user()->id); // Obtiene el usuario autenticado        

        Storage::delete('public/'.$user->profile_photo_path); // Eliminar imagen almacenada

        $user->profile_photo_path = null;        
        $user->save();
        
        //return redirect()->route('institucion.perfil');
        return redirect($request->ruta);

        //return response()->json(['message' => 'Error al actualizar'], 400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function perfilupdate(Request $request)
    {
        //dd($request->all());

        $user = User::find(Auth::user()->id); // Obtiene el usuario autenticado  
        $asesor = $user->asesor;

        //dd($request->all());        

        $request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            //'name' => ['required', 'string', 'min:5', 'max:50', Rule::unique('competencias')->ignore($competencia)],
            //'name' => ['required', 'string', 'min:5', 'max:50'],            
            'email' => ['required', 'email', new ValidateUniqueInTables(['users', 'registro_jueces'], $asesor->email)], //| unique:registro_jueces,email",
            'telefono' => ['nullable','numeric', Rule::unique('users', 'telefono')->ignore($user)],
            //'fecha' => ['date', 'before_or_equal:' . now()->addYears(2)->format('Y-m-d')],
            //'duracion' => ['required','integer','min:1','max:100'],
            //'asesor_id' => ['required', 'not_in:Selecciona una opción'],
            //'tipo' => ['required'],
            //'categoria_id' => ['required'],
            //'imagen' => ['image', 'mimes:png,jpg,jpeg', 'max:5120'], // Máximo 5 Mb
        ]);
        

        if($request->email_confirmation){
            // Elimina 'email_confirmation' del request
            $request->request->remove('email_confirmation');
        }

        //dd($request->all());
          
        // Enviar automáticamente el correo de verificación
        //event(new Registered($user));

        $confirmarCorreo = false;

        if($asesor->email != $request->email){
            $confirmarCorreo = true;
        }                    


        /*if ($request->hasFile('imagen')) {
            //dd($request);
            $request -> merge([
                'ubicacion_imagen' => $request->file('imagen')->storeAs('public/imagenes_competencias', 'Portada_'.$request->name.'.'. $request->file('imagen')->extension()),                
            ]);
        }*/     
        
        //dd($request->all());

        Asesor::where('id', $asesor->id)->update($request->except('_token','_method','ruta'));
        
        
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->telefono = $request->telefono;

        if($confirmarCorreo == true){
            $user->email_verified_at = null;
        }

        $user->save();


        if($confirmarCorreo == true){
            $user->sendEmailVerificationNotification();
        }

        // Actualizar tabla pivote con los nuevos registros  
        //$competencia->categorias()->sync($request->input('categoria_id'));

        // Insertar en la tabla pivote relacion m:n --> PENDIENTE FINAL
        //$competencia->categorias()->attach($request->categoria_id); //detach() elimina de la lista el usuario que le pasemos


        //Competencia::where('id', $competencia->id)->update($request->except('_token','_method')); //opuesto de except (only)

        //return redirect() -> route('categoria.show', $categoria); //esto corresponde a el listado de route:list 
        
        //return redirect() -> route('competencia.index'); //esto corresponde a el listado de route:list         

        //return redirect($request->ruta); 
        
        // Configura los datos para la notificación
        //session()->flash('alerta', [   
        
        session()->put('alerta', [                
            'texto' => 'Perfil Actualizado Exitosamente!',
            'icono' => 'success',
            'tiempo' => 2000,
            'botonConfirmacion' => false,
        ]);
        
        /*$previousUrl = session('_custom_previous.url');

        //return redirect() -> route('competencia.show', $competencia);
        return redirect($previousUrl);*/

        //return redirect($request->ruta);

        return redirect()->route('asesor.perfil');
    }


    // Usuario view
    public function vincularinstitucion()
    {
        if (Auth::check()) {
            // Si el usuario está autenticado, revisar que no tenga institucion
            $user = auth()->user();
            if(!$user->asesor->institucion_id && !$user->inst_independiente && !$user->asesor->asesor_institucion_solicitud){
                return view("asesor/vincularInstitucionAsesor"); 
            }

            return redirect('/');
        }
    
        // Si no está autenticado, regresar a inicio                
        return redirect('/');
    }

    // Usuario
    public function vincularinstitucionbusqueda(Request $request)
    {
        if (Auth::check()) {
            // Si el usuario está autenticado, revisar que no tenga institucion
            $user = auth()->user();
            if(!$user->asesor->institucion_id && !$user->inst_independiente && !$user->asesor->asesor_institucion_solicitud){

                //dd($request->busqueda);

                // Validar los campos
                $request->validate([            
                    //'codigo_rechazo' => 'required|min:15|max:15|exists:asesores,codigo_rechazo',            
                    'busqueda' => 'required|string',            
                ]);

                //dd($request->busqueda);

                // Normaliza el término de búsqueda (quita acentos y baja a minúsculas)
                $busqueda = quitar_acentos(strtolower($request->busqueda));

                // Buscar el código de registro en la base de datos
                // Trae todas las instituciones y filtra en PHP
                $coincidencias = Institucion::all()->filter(function($inst) use ($busqueda) {
                    // Normaliza nombre y código de cada registro
                    $nombreNorm = quitar_acentos(strtolower($inst->name));            

                    // Coincide si el término está contenido en uno u otro
                    return str_contains($nombreNorm, $busqueda);             
                });

                //dd($coincidencias->count());

                if($coincidencias->count()){                    
                    //return redirect() -> route('asesor.validarcredencialrechazada', $asesor->codigo_rechazo);

                    // Envía resultados a la vista
                    return view('asesor/busquedaVincularInstitucionAsesor', [
                        'coincidencias' => $coincidencias,
                        'busqueda' => $request->busqueda,
                    ]);
                }
                else{
                    // Configura los datos para la notificación
                    session()->flash('alerta', [
                        'titulo' => '"No se encontro ninguna coincidencia"',            
                        'texto' => 'Por favor ingrese el nombre de una institución valida con registro previo dentro de la plataforma.',
                        'icono' => 'error',
                        //'tiempo' => 5000,
                        'botonConfirmacion' => true,            
                    ]);

                    //dd($request->all());

                    return redirect() -> route('asesor.vincularinstitucion');
                }

            }

            return redirect('/');
        }
    
        // Si no está autenticado, regresar a inicio                
        return redirect('/');
    }

    public function vincularinstitucionstore(Request $request)
    {
        if (Auth::check()) {
            // Si el usuario está autenticado, revisar que no tenga institucion
            $user = auth()->user();
            if(!$user->asesor->institucion_id && !$user->inst_independiente && !$user->asesor->asesor_institucion_solicitud){

                $asesorinstitucionsolicitud = new AsesorInstitucionSolicitud();

                // Crear el registro en la base de datos

                //dd($user->asesor->id);
                
                $asesorinstitucionsolicitud->asesor_id = $user->asesor->id;
                $asesorinstitucionsolicitud->institucion_id = $request->institucion;   

                $asesorinstitucionsolicitud->save();     
                
                session()->put('alerta', [                
                    'titulo' => 'Solicitud enviada exitosamente!',
                    'texto' => 'Ya se ha notificado a la institucion que deseas vincular tu cuenta de asesor como parte de su institucion.',
                    'icono' => 'success',
                    'tiempo' => 3000,
                    'botonConfirmacion' => false,
                ]);
        
                return redirect()->route('asesor.perfil');
                
            }

            return redirect('/');
        }
    
        // Si no está autenticado, regresar a inicio                
        return redirect('/');
    }


    public function cancelarsolicitudinstitucion(AsesorInstitucionSolicitud $asesorinstitucionsolicitud)
    {        
        //dd($asesorinstitucionsolicitud);
                
        $asesorinstitucionsolicitud -> delete();

        session()->put('alerta', [                
            'titulo' => 'Solicitud cancelada exitosamente!',
            'texto' => 'La solicitud fue dada de baja correctamente, para hacer una nueva solicitud ingresa nuevamente a Vincular Institución.',
            'icono' => 'success',
            'tiempo' => 3000,
            'botonConfirmacion' => false,
        ]);
        
        return redirect()->route('asesor.perfil');
    }


    public function listadoasesores()
    {
        if (auth()->check()) { // Verifica si el usuario está logueado
            
            $user = auth()->user();

            if ($user->rol == 5){
       
                $asesores = Asesor::where('institucion_id',$user->institucion->id)->get(); //registros que solo pertenezcan al usuario logueado
                //dd($asesores);
                $asesorescount = $asesores->count();

                $solicitudesAsesores = AsesorInstitucionSolicitud::where('institucion_id',$user->institucion->id)->get();                     

                $solicitudesAsesorescount = $solicitudesAsesores->count();

                return view("asesor/indexAsesor",compact('asesores', 'asesorescount', 'solicitudesAsesorescount')); //<----- regresar vista al llamar al archivo index (asesor)
                //compact es para enviar al archhivo todos los datos de la variable asesores 

            } else{
                return redirect('/');
            }
        }
        else{
            return redirect('/');
        }    
    }

    // Administrador view
    public function solicitudasesores()
    {
        if (auth()->check()) { // Verifica si el usuario está logueado
            
            $user = auth()->user();

            if ($user->rol == 5) {                

                $solicitudesAsesores = AsesorInstitucionSolicitud::where('institucion_id',$user->institucion->id)->get();
                //->where('observaciones', '!=', 1) // Asegura que observaciones no sea 1      
                $asesorIds = $solicitudesAsesores->pluck('asesor_id'); // Obtiene solo los IDs                          

                $solicitudesAsesoresCount = $solicitudesAsesores->count();

                $cuentasAsesores = Asesor::whereIn('id',$asesorIds)
                ->orderBy('name', 'asc')
                ->get();

                //dd($cuentasAsesores);

                return view("asesor/revisarSolicitudAsesor",compact('cuentasAsesores', 'solicitudesAsesoresCount')); 

            } else{
                return redirect('/');
            }
        }
        else{
            return redirect('/');
        }                      
    }

    // Administrador view
    public function showsolicitudasesores(Asesor $asesor)
    {
        $cuentasAsesores = Asesor::where('verificada', 0)
        //->where('observaciones', '!=', 1) // Asegura que observaciones no sea 1
        ->where('observaciones', 0)
        ->orderBy('name', 'asc')
        ->get();

        // Encontrar el índice del asesor actual en la colección
        $indiceAsesorActual = $cuentasAsesores->search(function ($item) use ($asesor) {
            return $item->id === $asesor->id;
        });

        // Obtener el asesor anterior, si existe
        $asesorAnterior = $indiceAsesorActual > 0 ? $cuentasAsesores[$indiceAsesorActual - 1] : null;
        // Obtener el asesor siguiente, si existe
        $asesorSiguiente = $indiceAsesorActual < $cuentasAsesores->count() - 1 ? $cuentasAsesores[$indiceAsesorActual + 1] : null;

        //dd($asesorAnterior);
        //dd($asesorSiguiente);

        return view('asesor/showValidarCuentaAsesor',compact('asesor', 'asesorAnterior', 'asesorSiguiente')); //asesor es el usuario actual a mostrar
    }

    // Administrador
    public function aprobarsolicitud(Request $request, Asesor $asesor)
    {        

        $user = auth()->user();

        //dd($asesor->name);

        //dd($asesor->asesor_institucion_solicitud);

        $asesor->asesor_institucion_solicitud->delete();
        
        $asesor->institucion_id = $user->institucion->id;        

        $asesor->save();
        
        // Configura los datos para la notificación
        session()->flash('alerta', [
            'titulo' => '"Solicitud de Asesor aceptada exitosamente"',            
            //'texto' => 'La cuenta de ' . $asesor->email . ' fue activada exitosamente.',
            'html' => 'La solicitud de <b><i>' . $asesor->name .  '</i></b> fue aceptada exitosamente.',
            'icono' => 'success',
            'tiempo' => 3000,
            'botonConfirmacion' => false,            
        ]);

        if($request->listado){
            return redirect('/asesor/solicitud');
        }
        else{
            //Asesor anterior
            if($request->asesor_anterior_id){
                $asesorAnterior = Asesor::findOrFail($request->asesor_anterior_id);
            }else{
                $asesorAnterior = null;
            }

            //Asesor siguiente
            if($request->asesor_siguiente_id){
                $asesorSiguiente = Asesor::findOrFail($request->asesor_siguiente_id);
            }else{
                $asesorSiguiente = null;
            }

            //dd($asesorAnterior);
            //dd($asesorSiguiente);

            if($asesorSiguiente){
                return redirect() -> route('asesor.showvalidarcuenta', $asesorSiguiente);
            }
            elseif($asesorAnterior){
                return redirect() -> route('asesor.showvalidarcuenta', $asesorAnterior);
            }
            else{
                return redirect('/asesor/solicitud');
            }
        }        

        //return redirect('/administrador/trashed');
    }

    // Administrador
    public function rechazarsolicitud(Request $request, Asesor $asesor)
    {
        $asesor->asesor_institucion_solicitud->delete();      
        
        // CREAR VISTA DE CORRECCION DE DATOS POR MEDIO DE URL
        // volver a revisar los datos y volver a enviarlo a revision manual (bucle)

        // Enviar correo de rechazo de cuenta
        //Mail::to($asesor->email)->send(new NotificaCuentaAsesorRechazada($asesor, $observaciones));        

        // Configura los datos para la notificación
        session()->flash('alerta', [
            'titulo' => '"Solicitud de asesor rechzada correctamente"',
            //'texto' => 'La cuenta de ' . $asesor->email . ' fue activada exitosamente.',
            'html' => 'La solicitud de <b><i>' . $asesor->name .  '</i></b> fue rechazada satisfactoriamente.',
            'icono' => 'success',
            'tiempo' => 3000,
            'botonConfirmacion' => false,            
        ]);


        if($request->listado){
            return redirect('/asesor/solicitud');
        }
        else{
            //Asesor anterior
            if($request->asesor_anterior_id){
                $asesorAnterior = Asesor::findOrFail($request->asesor_anterior_id);
            }else{
                $asesorAnterior = null;
            }

            //Asesor siguiente
            if($request->asesor_siguiente_id){
                $asesorSiguiente = Asesor::findOrFail($request->asesor_siguiente_id);
            }else{
                $asesorSiguiente = null;
            }

            //dd($asesorAnterior);
            //dd($asesorSiguiente);

            if($asesorSiguiente){
                return redirect() -> route('asesor.showvalidarcuenta', $asesorSiguiente);
            }
            elseif($asesorAnterior){
                return redirect() -> route('asesor.showvalidarcuenta', $asesorAnterior);
            }
            else{
                return redirect('/asesor/solicitud');
            }
        }  
    

        //dd($observaciones);

        //Enviar correo de rechazo de cuenta

        //

        // Lógica para rechazar la cuenta y enviar notificaciones
        // Por ejemplo, actualizar el estado del asesor, guardar las observaciones, etc.
        //$asesor->observaciones = $data['observaciones'];
        //$asesor->estado = 'rechazado';
        //$asesor->save();

        // Redirigir al usuario a otra ruta, por ejemplo, a una página de confirmación
        //return redirect()->route('asesor.dashboard')->with('success', 'Cuenta rechazada y observaciones enviadas.');
    }
    

    public function desvincularinstitucion(Request $request, Asesor $asesor)
    {

        $institucion = $asesor->institucion;

        $asesor->institucion_id = null;        

        $asesor->save();      

        // Configura los datos para la notificación
        session()->flash('alerta', [
            'titulo' => '"Institucion desvinculada correctamente"',
            //'texto' => 'La cuenta de ' . $asesor->email . ' fue activada exitosamente.',
            'html' => 'La institucion <b><i>' . $institucion->name .  '</i></b> fue desvinculada de su cuenta.',
            'icono' => 'success',
            'tiempo' => 3000,
            'botonConfirmacion' => false,            
        ]);


        
        return redirect()->route('asesor.perfil');
    }

//=========================================================================================================================>


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
