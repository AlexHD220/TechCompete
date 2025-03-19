<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Competencia;
use App\Models\Equipo;
use App\Models\Institucion;
use Illuminate\Http\Request;

use App\Mail\NotificaEquipoRegistrado;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use App\Models\Administrador;
use App\Models\Team;
use App\Rules\ValidateUniqueInTables;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Rules\Password;

use Illuminate\Support\Str;

class InstitucionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $administradores = User::where('rol',5)->get();

        return view("institucion/indexInstitucion",compact('administradores')); 
    }

    public function create(Request $request)
    {              

        // Verificar si hay un paso guardado en la sesión
        if (session()->has('form.step')) {
            // Obtén el paso actual desde la sesión 
            $currentStep = session('form.step', 1);

            // Recuperar datos de la sesión para prellenar los campos si es necesario
            //$formData = session('form.data', []);

            $formPreviousData = session('form.previousData', []);

            if(session('form.previousData', $currentStep+1)){
                $previousData = True;
            }
            else{
                $previousData = False;
            }
        }
        else{
            // Reinicia la sesión para empezar desde el paso 1 siempre
            session()->forget('form');

            $currentStep = 1; // Reinicia al paso 1
            //$formData = []; 
            $formPreviousData = [];
            $previousData = False;
        }

        return view('institucion/createInstitucion', compact('currentStep', 'previousData','formPreviousData'));
    }
    
    public function reset()
    {
        // Limpiar los datos de la sesión
        session()->forget('form');

        // Redirigir al primer paso del formulario
        return redirect()->route('institucion.create'); 
    }
    
    public function anterior($valores)
    {
        /*// Recuperar los datos actuales del formulario
        $formData = $request->all(); 

        // Guardar los datos en la sesión
        session()->put('form', $formData); */

        // Paso actual
        $currentStep = session('form.step', 1);

        //dd($valores);
        
        // Decodificamos los valores de la URL
        $valores = json_decode(urldecode($valores), true);
        
        //$name = $valores['name'];
        
        //dd($valores);

        // Guardar los datos del paso actual en la sesión
        $formPreviousData = session('form.previousData', []);
        $formPreviousData["step_$currentStep"] = collect($valores)->except(['_token', 'step'])->toArray();
        session(['form.previousData' => $formPreviousData]);

        //dd($formPreviousData);            

        $currentStep--;        
        
        session(['form.step' => $currentStep]);

        // Redirigir al paso anterior del formulario
        return redirect()->route('institucion.create'); // O la ruta del paso anterior
    }

    public function store(Request $request)
    {
        // Paso actual
        $currentStep = $request->input('step', 1);

        // Validaciones específicas para cada paso
        if ($currentStep == 1) {
            $request->validate([
                'email' => ['required', 'email', new ValidateUniqueInTables(['users', 'registro_jueces'])], //| unique:registro_jueces,email",
                'whatsapp' => ['nullable','numeric','unique:users,telefono',],
                // Otras reglas de validación para otros campos
            ]);
        } 
        elseif ($currentStep == 2) {
            $request->validate([
                //'institucion' => 'required|string|max:255',
                //'direccion' => 'required|string|max:255',
                // Otras reglas de validación para otros campos
            ]);            
        } 
        elseif ($currentStep == 3) {
            $request->validate([
                //'descripcion' => 'required|string|max:1000',
                // Otras reglas de validación para otros campos
            ]);        
        }       
        elseif ($currentStep == 4) {
            $nombre_personalizado_errors = false;
            $nombre_credencial_errors = false;

            // Agregar la validación condicional
            if($request->nombre_escuela_credencial == 1){
                if($request->nombre_escuela_personalizado == 1){
                    if(!$request->nombre_credencial_escrito){
                        session()->flash('missing_nombre_credencial', true);
                        $nombre_credencial_errors = true;
                    }
                }
                elseif(!$request->nombre_escuela_personalizado){
                    session()->flash('missing_nombre_personalizado', true);
                    $nombre_personalizado_errors = true;
                }       
            }

            $request->validate([
                //'descripcion' => 'required|string|max:1000',
                // Otras reglas de validación para otros campos
            ]);  
            
            if ($nombre_credencial_errors || $nombre_personalizado_errors) {
                //dd($request->all());                  
                return redirect()->back()->withInput();
            }
        }
        elseif ($currentStep == 5) {
            $request->validate([
                //'descripcion' => 'required|string|max:1000',
                // Otras reglas de validación para otros campos
            ]);        
        } 

        // Guardar los datos del paso actual en la sesión
        $formData = session('form.data', []);
        $formData["step_$currentStep"] = $request->except(['_token', 'step']);
        session(['form.data' => $formData]);
    
        
        $valores = $request->input('valoresCodificados');    
        
        //dd($valores);

        $valores = json_decode(urldecode($valores), true);

        //dd($valores);

        // Guardar los datos del paso actual en la sesión
        $formPreviousData = session('form.previousData', []);
        $formPreviousData["step_$currentStep"] = collect($valores)->except(['_token', 'step'])->toArray();
        session(['form.previousData' => $formPreviousData]);

        //dd($formData);

        //dd($formData["step_1"]);

        // Si el usuario está en el último paso, procesar el formulario completo
        if ($currentStep == 5) {
            // Combinar todos los datos de los pasos
            $finalData = array_merge(...array_values($formData));
            //dd($finalData);

            // Convertir Array en Objeto
            $finalData = (object) $finalData; // Opuesto para convertir Objeto en Array es "$finalData = (array) $finalData;"
            //dd($finalData);

            $user = User::create([
                'rol' => 5,
                'name' => $finalData->name,
                'email' => $finalData->email,   
                'telefono' => $finalData->whatsapp,             
                'password' => Hash::make($finalData->password),                
            ]);
            
            //$this->createTeam($user);
    
            //$user->sendEmailVerificationNotification();
    
    
            // EQUIVALENTE --> Asesor::create($finalData->all()); 

            $googleMapsLink = "https://www.google.com/maps?q={$finalData->latitud},{$finalData->longitud}";
    
            $institucion = new Institucion(); //quiero una nueva instanciade este modelo que va a representar mi tabla (representante de alto nivel)
            $institucion->user_id = $user->id;
            $institucion->name = $finalData->name;
            $institucion->email = $finalData->email;
            $institucion->tipo = $finalData->tipo; //asignari atributos que corresonden por como se llaman mis columnas
            
            $institucion->pais = ucwords(strtolower($finalData->pais)); // ucwords() convierte la primera letra de cada palabra en mayúscula (strtoupper() para todo en mayúsculas / strtolower() para todo en minúsculas.)
            $institucion->estado = ucwords(strtolower($finalData->estado));
            $institucion->ciudad = ucwords(strtolower($finalData->ciudad));

            $institucion->domicilio = $finalData->domicilio;
            $institucion->latitud = $finalData->latitud;
            $institucion->longitud = $finalData->longitud;
            $institucion->mapa_link = $googleMapsLink;


            $institucion->pagina_web = $finalData->pagina_web;
            $institucion->telefono = $finalData->telefono;
            $institucion->whatsapp = $finalData->whatsapp;
            $institucion->email_contacto = $finalData->email_contacto;

            if($finalData->nombre_escuela_credencial == 1){
                $institucion->nombre_escuela_credencial = true;
                if($finalData->nombre_escuela_personalizado == 2){
                    $institucion->nombre_escuela_personalizado = true;
                    $institucion->nombre_credencial_escrito = $finalData->nombre_credencial_escrito;    
                }   
            }elseif($finalData->nombre_escuela_credencial == 2){
                $institucion->nombre_escuela_credencial = false;
            } 

            $institucion->save();
            
            // Enviar automáticamente el correo de verificación
            //event(new Registered($user));
            $user->sendEmailVerificationNotification();

            // Limpiar la sesión
            session()->forget('form');
            
            return redirect()->route('login')->with('success', '<b style="color: #41ef1f;">Su cuenta fue creada exitosamente.</b> <br> <i>Antes de continuar, por favor verifique su dirección de correo electrónico.</i>');
        }

        // Avanzar al siguiente paso
        $currentStep++;
        session(['form.step' => $currentStep]);

        return redirect()->route('institucion.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    /*public function originalCreate()
    {
        return view('institucion/respaldoCreateInstitucion');
    }*/

    /**
     * Store a newly created resource in storage.
     */
    /*public function originalStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:registro_jueces,email',
            'email' => ['unique:users'],
            // Otras reglas de validación para otros campos
        ]);

        $user = User::create([
            'rol' => 5,
            'name' => $request->name,
            'email' => $request->email,                
            'password' => Hash::make($request->password),                
        ]);
        
        //$this->createTeam($user);

        //$user->sendEmailVerificationNotification();


        // EQUIVALENTE --> Asesor::create($request->all()); 

        $institucion = new Institucion(); //quiero una nueva instanciade este modelo que va a representar mi tabla (representante de alto nivel)
        $institucion->user_id = $user->id;
        $institucion->name = $request->name;
        $institucion->email = $request->email;
        $institucion->tipo = $request->tipo;
        $institucion->pais = $request->pais;
        $institucion->region = $request->region;
        $institucion->pagina_web = $request->pagina_web;
        $institucion->telefono = $request->telefono;
        $institucion->whatsapp = $request->whatsapp;
        $institucion->save();
        
        // Enviar automáticamente el correo de verificación
        //event(new Registered($user));
        $user->sendEmailVerificationNotification();

        // Redirigir con un mensaje de éxito
        //return redirect()->route('login');
        return redirect()->route('login')->with('success', '<b style="color: #41ef1f;">Su cuenta fue creada correctamente.</b> <br> <i>Antes de continuar, por favor verifique su dirección de correo electrónico.</i>');
        
        //return redirect('/institucion'); 
    }*/


    public function perfil()
    {        
        $institucion = auth()->user()->institucion;

        //dd($institucion->user);

        return view('institucion/perfilInstitucion',compact('institucion')); 
    }

    public function ocultarPortada()
    {
        //$user = auth()->user(); // Obtiene el usuario autenticado
        $institucion = auth()->user()->institucion; // Relación User -> Institucion

        if ($institucion->portada_oculta == true) {
            $institucion->portada_oculta = false;
            $institucion->save();

            return;
        }
        else{
            $institucion->portada_oculta = true;
            $institucion->save();

            return;
        }

        //return response()->json(['message' => 'Error al actualizar'], 400);
    }

    public function actualizarPortada(Request $request)
    {
        //$user = auth()->user(); // Obtiene el usuario autenticado
        $institucion = auth()->user()->institucion; // Relación User -> Institucion

        $fileName = Str::slug($request->name, '_');
        $institucion->ubicacion_imagen = $request->file('imagenPortada')->storeAs('public/imagenes_instituciones', 'Portada_'.$fileName.'.'. $request->file('imagenPortada')->extension());
        $institucion->portada_oculta = false;
        $institucion->save();
        
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
        $institucion = auth()->user()->institucion;

        //dd($institucion->user);

        return view('institucion/editPerfilInstitucion',compact('institucion')); 
    }

    public function eliminarPortada(Request $request)
    {
        //$user = auth()->user(); // Obtiene el usuario autenticado
        $institucion = auth()->user()->institucion; // Relación User -> Institucion

        Storage::delete($institucion->ubicacion_imagen); // Eliminar imagen almacenada

        $institucion->ubicacion_imagen = null;
        $institucion->portada_oculta = false;
        $institucion->save();
        
        //return redirect()->route('institucion.perfil');
        return redirect($request->ruta);

        //return response()->json(['message' => 'Error al actualizar'], 400);
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
        $institucion = $user->institucion;

        //dd($request->all());        

        $request->validate([ ///Validar datos, si los datos recibidos no cumplen estas regresas no les permite la entrada a la base de datos y regresa a la pagina original
            //'name' => ['required', 'string', 'min:5', 'max:50', Rule::unique('competencias')->ignore($competencia)],
            //'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'email', new ValidateUniqueInTables(['users', 'registro_jueces'], $institucion->email)], //| unique:registro_jueces,email",
            'whatsapp' => ['nullable','numeric', Rule::unique('users', 'telefono')->ignore($user)],
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

        // Generar el enlace de Google Maps
        $googleMapsLink = "https://www.google.com/maps?q={$request->latitud},{$request->longitud}";
            
        $request -> merge([            
            'mapa_link' => $googleMapsLink,
        ]);
                    
        $request->merge(['pais' => ucwords(strtolower($request->pais))]); // ucwords() convierte la primera letra de cada palabra en mayúscula (strtoupper() para todo en mayúsculas / strtolower() para todo en minúsculas.)        
        $request->merge(['estado' => ucwords(strtolower($request->estado))]);         
        $request->merge(['ciudad' => ucwords(strtolower($request->ciudad))]);     

        //$request->merge(['' => $]);

        if($request->nombre_escuela_credencial == 1){
            $nombre_escuela_credencial = true;
            $request->merge(['nombre_escuela_credencial' => $nombre_escuela_credencial]);

            $request->merge(['ciudad' => ucwords(strtolower($request->ciudad))]);  
            if($request->nombre_escuela_personalizado == 2){
                $nombre_escuela_personalizado = true;     
                $request->merge(['nombre_escuela_personalizado' => $nombre_escuela_personalizado]);           
            }
            elseif($request->nombre_escuela_personalizado == 1){
                $nombre_escuela_personalizado = false;
                $request->merge(['nombre_escuela_personalizado' => $nombre_escuela_personalizado]);

                $nombre_credencial_escrito = null;

                $request -> merge([
                    'nombre_credencial_escrito' => $nombre_credencial_escrito,
                ]);
            }
        }elseif($request->nombre_escuela_credencial == 2){
            $nombre_escuela_credencial = false;
            $request->merge(['nombre_escuela_credencial' => $nombre_escuela_credencial]);

            $nombre_escuela_personalizado = false;
            $nombre_credencial_escrito = null;

            $request -> merge([            
                'nombre_escuela_personalizado' => $nombre_escuela_personalizado,
                'nombre_credencial_escrito' => $nombre_credencial_escrito,
            ]);
        }             
        
        // Enviar automáticamente el correo de verificación
        //event(new Registered($user));

        $confirmarCorreo = false;

        if($institucion->email != $request->email){
            $confirmarCorreo = true;
        }                    


        /*if ($request->hasFile('imagen')) {
            //dd($request);
            $request -> merge([
                'ubicacion_imagen' => $request->file('imagen')->storeAs('public/imagenes_competencias', 'Portada_'.$request->name.'.'. $request->file('imagen')->extension()),                
            ]);
        }*/     
        
        //dd($request->all());

        Institucion::where('id', $institucion->id)->update($request->except('_token','_method','ruta'));
        
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->telefono = $request->whatsapp;

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

        return redirect()->route('institucion.perfil');
    }


    /**
     * Display the specified resource.
     */
    public function show(Institucion $institucion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institucion $institucion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institucion $institucion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institucion $institucion)
    {
        //
    }
}
