<?php

use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/plantilla', function () {
    return view('plantilla');
});*/

Route::get('/plantilla', function () {
    if (Gate::allows('only-admin', auth()->user())) {
        return view('plantilla');
    } else {
        return redirect('/');
    }
});

Route::get('/correo', function () {
    return view('emails/notificaEquipo');
});

//Route::get('/contacto/{tipo?}',[SitioController::class,'contactoForm']);

//Route::post('usuario/createUsuario',[SitioController::class,'usuarioSave']);

//Route::get('usuario/pdf',[usuarioController::class,'pdf']) -> name('usuario.pdf'); //Ruta agregada de forma manual
//cabiar el nombre de mis rutas

Route::middleware('auth', 'verified')->group(function(){
    
    Route::resource('asesor', AsesorController::class);

    Route::resource('equipo', EquipoController::class);

    Route::resource('proyecto', ProyectoController::class);

    Route::resource('participante', ParticipanteController::class);

    Route::resource('participante', ParticipanteController::class);

    Route::resource('administrador', AdministradorController::class);
    
});

Route::resource('competencia', CompetenciaController::class)->parameters([
    'competencia' => 'competencia', //Corregir error {competencium} en -> php artisan route:list
]);

Route::resource('categoria', CategoriaController::class)->parameters([
    'categoria' => 'categoria',
]);



// Prueba
Route::get('/admin', function () {
    if (Gate::allows('adminAccess', auth()->user())) {
        return view('admin.index');
    } else {
        return redirect('/otra-pagina');
    }
});

// Prueba
Route::resource('usuario', UsuarioController::class); //este hace que el CRUD sirva hay que agregarlo por cada tabla

//Route::resource('asesor', AsesorController::class);

//Proteccion del dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


//Route::post('/logout', 'Auth\LoginController@logout')->name('logout'); //ERROR NO FUNCIONO