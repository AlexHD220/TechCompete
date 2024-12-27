<?php

namespace App\Http\Controllers;

use App\Mail\NotificaCodigoregistrojuez;
use App\Models\registrojuez;
use Illuminate\Http\Request;

use App\Models\Administrador;
use App\Models\Juez;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth; //ID Usuario
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class registrojuezController extends Controller
{

    protected $model = Juez::class;

    public function __construct()
    {
        $this->middleware('can:only-superadmin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrojueces = registrojuez::where('validado',0)->where('expiracion_date', '>', Carbon::now())
        ->orderBy('expiracion_date', 'asc')->get(); // Si quiero ordenarlos de mayor a menos usar desc

        foreach ($registrojueces as $registro) {
            $registro->diasrestantes = Carbon::now()->diffInDays(Carbon::parse($registro->expiracion_date), false);

            /*if($registro->diasrestantes == 0){                
                $registro->horasrestantes = Carbon::now()->diffInHours(Carbon::parse($registro->expiracion_date), false);
            }*/
        }

        $expirados = registrojuez::whereNotNull('expiracion_date')
        ->where('expiracion_date', '<', Carbon::now())->get();

        $expiradoscount = $expirados->count();

        return view("registrojuez/indexregistrojuez",compact('registrojueces', 'expiradoscount')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registrojuez/createregistrojuez');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:users,email',
            // Otras reglas de validación para otros campos
        ]);

        $registrojuez = new registrojuez();

        //dd($registrojuez);

         // Generar un código único
        $codigo = $this->generateUniqueCode();

        // Crear el registro en la base de datos
        $registrojuez->codigo = $codigo;
        $registrojuez->email = $request->email;
        $registrojuez->creado_by = auth()->id(); // Asigna el ID del usuario autenticado        
        $registrojuez->creacion_date = Carbon::now();

        // Manejar la expiración según el tipo seleccionado
        if ($request->expiration_type === 'days') {
            $registrojuez->expiracion_date = Carbon::now()->addDays($request->days);
        } elseif ($request->expiration_type === 'specific_date') {
            $registrojuez->expiracion_date = Carbon::parse($request->specific_date);
        }

        $registrojuez->expiracion_date->addDays(1);

        //dd($registrojuez->id);
        
        $registrojuez->save();

        //$user->sendEmailVerificationNotification();

        // Notificar por email que el asesor se creo correctamente
        Mail::to($registrojuez->email)->send(new NotificaCodigoregistrojuez($registrojuez));
        
        return redirect('/registrojuez'); 
    }

    /**
     * Genera un código único que no exista en la base de datos.
     */
    private function generateUniqueCode($length = 15)
    {
        do {
            $codigo = Str::random($length); // Genera un código aleatorio alfanumérico
        } while (registrojuez::where('codigo', $codigo)->exists()); // Verifica si el código ya existe

        return $codigo;
    }

    /**
     * Display the specified resource.
     */
    public function show(registrojuez $registrojuez)
    {
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(registrojuez $registrojuez)
    {
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, registrojuez $registrojuez)
    {
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(registrojuez $registrojuez)
    {
        //dd($registrojuez);
        
        $registrojuez->delete();

        return redirect('/registrojuez');
    }

    public function destroyexpirados()
    {
        // Elimina los registros expirados
        registrojuez::where(function ($query){
            $query->whereNotNull('expiracion_date')->where('expiracion_date', '<', Carbon::now());            
        })->delete();

        // Redirige con un mensaje de éxito
        return redirect('/registrojuez');
    }

    public function reenviarcorreo(registrojuez $registrojuez)
    {
        //dd($registrojuez);
        
        Mail::to($registrojuez->email)->send(new NotificaCodigoregistrojuez($registrojuez));

        return redirect('/registrojuez');
    }
}
