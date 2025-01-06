<?php

use App\Http\Controllers\AccesoCompetenciaController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompetenciaCategoriaController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\JuecesCompetenciaController;
use App\Http\Controllers\JuezController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\RegistroJuezController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UsuarioController;
use App\Models\Staff;
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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', function () {
    if (Gate::allows('autenticado')) {
        // Usuario autenticado
        if (Gate::allows('mail-verificado', auth()->user())) {
            return view('welcome'); // Usuario con correo verificado
        } else {
            return redirect()->route('verification.notice'); // Usuario sin correo verificado
        }
    } else {
        // Usuario no autenticado
        return view('welcome'); // Usuario con correo verificado        
    }
})->name('inicio');

Route::get('/home', function () {
    return view('home-page');
})->name('home');


/*Route::get('/plantilla', function () {
    return view('plantilla');
});*/

Route::get('/plantilla', function () {
    if (Gate::allows('only-superadmin', auth()->user())) {
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

Route::middleware('auth', 'verified')->group(function(){  // Necesitan iniciar sesion y estar verificados (mail)

    Route::resource('equipo', EquipoController::class);

    Route::resource('proyecto', ProyectoController::class);

    Route::resource('participante', ParticipanteController::class);
    

//------------------------------------------------------------------------------------> Administrador

    // Ruta personalizada para "hard destroy"
    Route::delete('administrador/{administrador}/harddestroy', [AdministradorController::class, 'harddestroy']) //Nombre del metodo controller
    ->name('administrador.harddestroy');

    // Ruta personalizada para "disabled hard destroy"
    Route::delete('administrador/{id}/disabledharddestroy', [AdministradorController::class, 'disabledharddestroy']) //Nombre del metodo controller
    ->name('administrador.disabledharddestroy');

    // Ruta para mostrar los registros eliminados
    Route::get('administrador/trashed', [AdministradorController::class, 'trashed'])
    ->name('administrador.trashed');

    // Ruta para restaurar un registro
    Route::patch('administrador/{id}/restore', [AdministradorController::class, 'restore'])
    ->name('administrador.restore');

    Route::patch('administrador/{administrador}/upper', [AdministradorController::class, 'makeUpper'])
    ->name('administrador.upper');

    Route::patch('administrador/{administrador}/lower', [AdministradorController::class, 'makeLower'])
    ->name('administrador.lower');

    Route::resource('administrador', AdministradorController::class);

//------------------------------------------------------------------------------------|


//------------------------------------------------------------------------------------> Staff

    // Ruta personalizada para "hard destroy"
    Route::delete('staff/{staff}/harddestroy', [StaffController::class, 'harddestroy']) //Nombre del metodo controller
    ->name('staff.harddestroy');

    // Ruta personalizada para "disabled hard destroy"
    Route::delete('staff/{id}/disabledharddestroy', [StaffController::class, 'disabledharddestroy']) //Nombre del metodo controller
    ->name('staff.disabledharddestroy');

    // Ruta para mostrar los registros eliminados
    Route::get('staff/trashed', [StaffController::class, 'trashed'])
    ->name('staff.trashed');

    // Ruta para restaurar un registro
    Route::patch('staff/{id}/restore', [StaffController::class, 'restore'])
    ->name('staff.restore');

    Route::patch('staff/{staff}/upper', [StaffController::class, 'makeUpper'])
    ->name('staff.upper');

    Route::patch('staff/{staff}/lower', [StaffController::class, 'makeLower'])
    ->name('staff.lower');

    Route::resource('staff', StaffController::class);

//------------------------------------------------------------------------------------|

    
//------------------------------------------------------------------------------------> Juez

    Route::get('juez/create/{codigo}', [JuezController::class, 'createjuez'])
    ->name('juez.createjuez');

    // Ruta personalizada para "hard destroy"
    Route::delete('juez/{juez}/harddestroy', [JuezController::class, 'harddestroy']) //Nombre del metodo controller
    ->name('juez.harddestroy');

    // Ruta personalizada para "disabled hard destroy"
    Route::delete('juez/{id}/disabledharddestroy', [JuezController::class, 'disabledharddestroy']) //Nombre del metodo controller
    ->name('juez.disabledharddestroy');

    // Ruta para mostrar los registros eliminados
    Route::get('juez/trashed', [JuezController::class, 'trashed'])
    ->name('juez.trashed');

    // Ruta para restaurar un registro
    Route::patch('juez/{id}/restore', [JuezController::class, 'restore'])
    ->name('juez.restore');

    Route::resource('juez', JuezController::class);

//------------------------------------------------------------------------------------|

//------------------------------------------------------------------------------------> Registro Juez

    Route::delete('registrojuez/destroyexpirados', [RegistroJuezController::class, 'destroyexpirados']) //Nombre del metodo controller
    ->name('registrojuez.destroyexpirados');

    Route::post('reenviarcorreo/{registrojuez}', [RegistroJuezController::class, 'reenviarcorreo'])
    ->name('registrojuez.reenviarcorreo');

    Route::resource('registrojuez', RegistroJuezController::class);

//------------------------------------------------------------------------------------|


    Route::resource('accesocompetencia', AccesoCompetenciaController::class)->parameters([
        'accesocompetencia' => 'accesocompetencia', //Corregir error {competencium} en -> php artisan route:list
    ]);

    Route::resource('juecescompetencia', JuecesCompetenciaController::class)->parameters([
        'juecescompetencia' => 'juecescompetencia', //Corregir error {competencium} en -> php artisan route:list
    ]);

//------------------------------------------------------------------------------------> Competencia

    // Ruta personalizada para "hard destroy"
    Route::delete('competencia/{competencia}/harddestroy', [CompetenciaController::class, 'harddestroy']) //Nombre del metodo controller
    ->name('competencia.harddestroy');

    // Ruta personalizada para "disabled hard destroy"
    Route::delete('competencia/{id}/disabledharddestroy', [CompetenciaController::class, 'disabledharddestroy']) //Nombre del metodo controller
    ->name('competencia.disabledharddestroy');

    // Ruta para mostrar los registros pendientes de publicar
    Route::get('competencia/drafts', [CompetenciaController::class, 'drafts'])
    ->name('competencia.drafts');

    // Ruta para mostrar los registros eliminados
    Route::get('competencia/trashed', [CompetenciaController::class, 'trashed'])
    ->name('competencia.trashed');

    // Ruta para mostrar los registros anteriores
    Route::get('competencia/previous', [CompetenciaController::class, 'previous'])
    ->name('competencia.previous');

    // Ruta para restaurar un registro
    Route::patch('competencia/{id}/restore', [CompetenciaController::class, 'restore'])
    ->name('competencia.restore');

    // Ruta para mostrar los detalles de registros eliminados
    Route::get('competencias/trashed/{id}', [CompetenciaController::class, 'showtrashed'])
    ->name('competencias.showtrashed');

//------------------------------------------------------------------------------------|
    
});

Route::resource('competencia', CompetenciaController::class)->parameters([
    'competencia' => 'competencia', //Corregir error {competencium} en -> php artisan route:list
]);

Route::resource('categoria', CategoriaController::class)->parameters([
    'categoria' => 'categoria',
]);

Route::resource('competenciacategoria', CompetenciaCategoriaController::class)->parameters([
    'competenciacategoria' => 'competenciacategoria', //Corregir error {competencium} en -> php artisan route:list
]);


Route::resource('institucion', InstitucionController::class);

Route::resource('asesor', AsesorController::class);


Route::resource('horario', HorarioController::class);


// Prueba gates
Route::get('/admin', function () {
    if (Gate::allows('adminAccess', auth()->user())) {
        return view('admin.index');
    } else {
        return redirect('/otra-pagina');
    }
});

// Prueba rutas
//Route::resource('usuario', UsuarioController::class); //este hace que el CRUD sirva hay que agregarlo por cada tabla

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


//Route::post('/logout', 'Auth\LoginController@logout')->name('logout'); // <-- ERROR ESTO NO FUNCIONO

Route::get('/type-register', function () {
    return view('type-register');
})->name('type-register');
