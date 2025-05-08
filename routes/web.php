<?php

use App\Http\Controllers\AccesoCompetenciaController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AsesorController;
use App\Models\AsesorInstitucionSolicitud;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompetenciaCategoriaController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\CompetenciaSubcategoriaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\JuecesCompetenciaController;
use App\Http\Controllers\JuezController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\RegistroJuezController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SubcategoriaController;
use App\Http\Controllers\UsuarioController;
use App\Models\Asesor;
use App\Models\CompetenciaSubcategoria;
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
    session()->forget('form');

    if (Gate::allows('autenticado')) {
        // Usuario autenticado
        if (Gate::allows('mail-verificado', auth()->user())) {                                  
            
            $user = auth()->user();

            if ($user->rol == 1 || $user->rol == 2) {

                $cuentasAsesores = Asesor::where('verificada',0)->where('observaciones', 0)->get();                     

                $cuentasAsesorescount = $cuentasAsesores->count();

                return view('welcome',compact('cuentasAsesorescount')); 

            } else if ($user->rol == 5) {
                $solicitudesAsesores = AsesorInstitucionSolicitud::where('institucion_id',$user->institucion->id)->get();                     

                $solicitudesAsesorescount = $solicitudesAsesores->count();

                //dd($solicitudesAsesorescount);

                return view('welcome',compact('solicitudesAsesorescount')); 

            } else{
                return view('welcome'); // Usuario con correo verificado
            }

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


Route::get('/pruebas', function () {
    if (Gate::allows('only-superadmin', auth()->user())) {
        return view('pruebas');
    } else {
        return redirect('/');
    }
});
    

Route::middleware('auth', 'verified')->group(function(){  // Necesitan iniciar sesion y estar verificados (mail)

    Route::resource('equipo', EquipoController::class);

    Route::resource('proyecto', ProyectoController::class);

    Route::resource('participante', ParticipanteController::class);
    

//------------------------------------------------------------------------------------> Administrador middleware

    // Ruta personalizada para "hard destroy"
    Route::delete('administrador/{administrador}/harddestroy', [AdministradorController::class, 'harddestroy']) //Nombre del metodo controller
    ->name('administrador.harddestroy');

    // Ruta personalizada para "trashed hard destroy"
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


//------------------------------------------------------------------------------------> Staff middleware

    // Ruta personalizada para "hard destroy"
    Route::delete('staff/{staff}/harddestroy', [StaffController::class, 'harddestroy']) //Nombre del metodo controller
    ->name('staff.harddestroy');

    // Ruta personalizada para "trashed hard destroy"
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

    
//------------------------------------------------------------------------------------> Juez middleware

    Route::get('juez/signup/{codigo}', [JuezController::class, 'create'])
    ->name('juez.create');
    //->name('juez.createjuez');

    // Ruta personalizada para "hard destroy"
    Route::delete('juez/{juez}/harddestroy', [JuezController::class, 'harddestroy']) //Nombre del metodo controller
    ->name('juez.harddestroy');

    // Ruta personalizada para "trashed hard destroy"
    Route::delete('juez/{id}/disabledharddestroy', [JuezController::class, 'disabledharddestroy']) //Nombre del metodo controller
    ->name('juez.disabledharddestroy');

    // Ruta para mostrar los registros eliminados
    Route::get('juez/trashed', [JuezController::class, 'trashed'])
    ->name('juez.trashed');

    // Ruta para restaurar un registro
    Route::patch('juez/{id}/restore', [JuezController::class, 'restore'])
    ->name('juez.restore');

    Route::resource('juez', JuezController::class)->except(['create']); // Dejar fuera la ruta create para agregarla manualmente 

//------------------------------------------------------------------------------------|

//------------------------------------------------------------------------------------> Registro Juez middleware

    Route::delete('registrojuez/destroyexpirados', [RegistroJuezController::class, 'destroyexpirados']) //Nombre del metodo controller
    ->name('registrojuez.destroyexpirados');

    Route::post('registrojuez/reenviarcorreo/{registrojuez}', [RegistroJuezController::class, 'reenviarcorreo'])
    ->name('registrojuez.reenviarcorreo');

    Route::resource('registrojuez', RegistroJuezController::class);

//------------------------------------------------------------------------------------|


    Route::resource('accesocompetencia', AccesoCompetenciaController::class)->parameters([
        'accesocompetencia' => 'accesocompetencia', //Corregir error {competencium} en -> php artisan route:list
    ]);

    Route::resource('juecescompetencia', JuecesCompetenciaController::class)->parameters([
        'juecescompetencia' => 'juecescompetencia', //Corregir error {competencium} en -> php artisan route:list
    ]);

//------------------------------------------------------------------------------------> Competencia middleware

    /*// Ruta personalizada para "hard destroy"
    Route::delete('competencia/{competencia}/harddestroy', [CompetenciaController::class, 'harddestroy']) //Nombre del metodo controller
    ->name('competencia.harddestroy');

    // Ruta personalizada para "trashed hard destroy"
    Route::delete('competencia/{id}/disabledharddestroy', [CompetenciaController::class, 'disabledharddestroy']) //Nombre del metodo controller
    ->name('competencia.disabledharddestroy');*/


    /*// Ruta para mostrar los borradores eliminados
    Route::get('competencia/drafttrashed', [CompetenciaController::class, 'drafttrashed'])
    ->name('competencia.drafttrashed');

    // Ruta para mostrar los detalles de borradores eliminados
    Route::get('competencias/drafttrashed/{competencia}', [CompetenciaController::class, 'showdrafttrashed'])
    ->name('competencias.showdrafttrashed');*/

    
    /*// Ruta para activar un registro
    Route::patch('competencia/{competencia}/enabled', [CompetenciaController::class, 'enabled'])
    ->name('competencia.enabled');

    // Ruta para desactivar un registro
    Route::patch('competencia/{competencia}/disabled', [CompetenciaController::class, 'disabled'])
    ->name('competencia.disabled');*/

    
    // Ruta para mostrar los registros pendientes de publicar
    Route::get('competencia/draft', [CompetenciaController::class, 'draft'])
    ->name('competencia.draft');

    // Ruta para mostrar los registros eliminados
    Route::get('competencia/trashed', [CompetenciaController::class, 'trashed'])
    ->name('competencia.trashed');

    // Ruta para restaurar un registro eliminado
    Route::patch('competencia/{id}/restore', [CompetenciaController::class, 'restore'])
    ->name('competencia.restore');

    // Ruta para mostrar los detalles de registros no publicados
    Route::get('competencia/draft/{competencia}', [CompetenciaController::class, 'showdraft'])
    ->name('competencia.showdraft');

    // Ruta para mostrar los detalles de registros eliminados
    Route::get('competencia/trashed/{competenciaid}', [CompetenciaController::class, 'showtrashed'])
    ->name('competencia.showtrashed');         

    // Ruta para publicar un registro
    Route::patch('competencia/{competencia}/publicar', [CompetenciaController::class, 'publicar'])
    ->name('competencia.publicar');

    // Ruta Destroy mofificada
    /*Route::delete('competencia/{competencia}', [CompetenciaController::class, 'destroy'])
    ->name('competencia.destroy');*/

    Route::delete('competencia/destroyexpiradas', [CompetenciaController::class, 'destroyexpiradas']) //Nombre del metodo controller
    ->name('competencia.destroyexpiradas');

    // Ruta para desactivar un registro
    Route::patch('competencia/{competencia}/ocultar', [CompetenciaController::class, 'ocultar'])
    ->name('competencia.ocultar');

    // Ruta para mostrar los registros pendientes de publicar
    Route::get('competencia/draft/{competencia}/edit', [CompetenciaController::class, 'editdraft'])
    ->name('competencia.editdraft');

    Route::patch('competencia/trashed{id}/updateName', [CompetenciaController::class, 'updateName'])
    ->name('competencia.updateName');


//------------------------------------------------------------------------------------|

//------------------------------------------------------------------------------------> Categorias middleware

    /*// Ruta personalizada para "hard destroy"
    Route::delete('categoria/{categoria}/harddestroy', [CategoriaController::class, 'harddestroy']) //Nombre del metodo controller
    ->name('categoria.harddestroy');

    // Ruta personalizada para "trashed hard destroy"
    Route::delete('categoria/{id}/disabledharddestroy', [CategoriaController::class, 'disabledharddestroy']) //Nombre del metodo controller
    ->name('categoria.disabledharddestroy');*/

    // Ruta para mostrar los registros eliminados
    Route::get('categoria/trashed', [CategoriaController::class, 'trashed'])
    ->name('categoria.trashed');

    // Ruta para restaurar un registro eliminado
    Route::patch('categoria/{id}/restore', [CategoriaController::class, 'restore'])
    ->name('categoria.restore');  

    /*// Ruta para desactivar un registro
    Route::patch('categoria/{categoria}/ocultar', [CategoriaController::class, 'ocultar'])
    ->name('categoria.ocultar');*/

//------------------------------------------------------------------------------------|

//------------------------------------------------------------------------------------> Categorias

    Route::resource('categoria', CategoriaController::class)->parameters([
        'categoria' => 'categoria',
    ]);

//------------------------------------------------------------------------------------|

//------------------------------------------------------------------------------------> Subcategorias

    Route::get('subcategoria', [SubcategoriaController::class, 'index'])
    ->name('subcategoria.create');

    Route::resource('subcategoria', SubcategoriaController::class)->parameters([
        'subcategoria' => 'subcategoria', //Corregir error {competencium} en -> php artisan route:list
    ])->except(['index','create','show']);

//------------------------------------------------------------------------------------|

//------------------------------------------------------------------------------------> Competencia_Categoria

    Route::get('competencia/{competencia}/categoria/attach', [CompetenciaCategoriaController::class, 'create'])
    ->name('competenciacategoria.create');    

    Route::post('competencia/{competencia}/categoria', [CompetenciaCategoriaController::class, 'store'])
    ->name('competenciacategoria.store');
        
    // Ruta para mostrar los registros pendientes de publicar
    Route::get('competencia/{competencia}/categoria/{competenciaCategoria}/edit', [CompetenciaCategoriaController::class, 'edit'])
    ->name('competenciacategoria.edit');

    Route::patch('competencia/{competencia}/categoria/{competenciaCategoria}', [CompetenciaCategoriaController::class, 'update'])
    ->name('competenciacategoria.update');

    // Ruta Destroy 
    Route::delete('competencia/{competencia}/categoria/{competenciaCategoria}', [CompetenciaCategoriaController::class, 'destroy'])
    ->name('competenciacategoria.destroy');    

    Route::get('competencia/draft/{competencia}/categoria/attach', [CompetenciaCategoriaController::class, 'createdraft'])
    ->name('competenciacategoria.createdraft');

    // Ruta para mostrar los detalles de registros no publicados
    Route::get('competencia/draft/{competencia}/categoria/{competenciaCategoria}', [CompetenciaCategoriaController::class, 'showdraft'])
    ->name('competenciacategoria.showdraft');    

    // Ruta para mostrar los registros pendientes de publicar
    Route::get('competencia/draft/{competencia}/categoria/{competenciaCategoria}/edit', [CompetenciaCategoriaController::class, 'editdraft'])
    ->name('competenciacategoria.editdraft');

    // Ruta para mostrar los detalles de registros eliminados
    Route::get('competencia/trashed/{competenciaid}/categoria/{categoriaid}', [CompetenciaCategoriaController::class, 'showtrashed'])
    ->name('competenciacategoria.showtrashed'); 

//------------------------------------------------------------------------------------|

//------------------------------------------------------------------------------------> Competencia_Subcategoria

    Route::get('competencia/{competencia}/categoria/{competenciaCategoria}/subcategoria/attach', [CompetenciaSubcategoriaController::class, 'create'])
    ->name('competenciasubcategoria.create');

    Route::post('competencia/{competencia}/categoria/{competenciaCategoria}/subcategoria', [CompetenciaSubcategoriaController::class, 'store'])
    ->name('competenciasubcategoria.store');

    // Ruta para mostrar los registros pendientes de publicar
    Route::get('competencia/{competencia}/categoria/{competenciaCategoria}/subcategoria/{competenciaSubcategoria}/edit', [CompetenciaSubcategoriaController::class, 'edit'])
    ->name('competenciasubcategoria.edit');

    Route::patch('competencia/{competencia}/categoria/{competenciaCategoria}/subcategoria/{competenciaSubcategoria}', [CompetenciaSubcategoriaController::class, 'update'])
    ->name('competenciasubcategoria.update');

    // Ruta Destroy 
    Route::delete('competencia/{competencia}/categoria/{competenciaCategoria}/subcategoria/{competenciaSubcategoria}', [CompetenciaSubcategoriaController::class, 'destroy'])
    ->name('competenciasubcategoria.destroy');    

    Route::get('competencia/draft/{competencia}/categoria/{competenciaCategoria}/subcategoria/attach', [CompetenciaSubcategoriaController::class, 'createdraft'])
    ->name('competenciasubcategoria.createdraft');    

    // Ruta para mostrar los detalles de registros no publicados [PEDNIENTE DE IMPLEMENTAR]
    /*Route::get('competencia/draft/{competencia}/categoria/{competenciaCategoria}/subcategoria/{competenciaSubcategoria}', [CompetenciaSubcategoriaController::class, 'showdraft'])
    ->name('competenciasubcategoria.showdraft');*/

    // Ruta para mostrar los registros pendientes de publicar
    Route::get('competencia/draft/{competencia}/categoria/{competenciaCategoria}/subcategoria/{competenciaSubcategoria}/edit', [CompetenciaSubcategoriaController::class, 'editdraft'])
    ->name('competenciasubcategoria.editdraft');
    
    //---------------------------------------> Pendientes de inplementar [!]

//------------------------------------------------------------------------------------|

    Route::get('cuenta/configuracion', function () {
        session()->forget('form');

        if (Gate::allows('autenticado')) {
            // Usuario autenticado
            if (Gate::allows('mail-verificado', auth()->user())) {
                return view('profile.show'); // Usuario con correo verificado
            } else {
                return redirect()->route('verification.notice'); // Usuario sin correo verificado
            }
        } else {
            // Usuario no autenticado
            return view('welcome'); // Usuario con correo verificado        
        }
    })->name('configuracionPerfil');

//------------------------------------------------------------------------------------> Perfil institucion

    Route::get('institucion/perfil', [InstitucionController::class, 'perfil'])
    ->name('institucion.perfil');

    Route::post('/institucion/perfil/ocultarPortada', [InstitucionController::class, 'ocultarPortada'])
    ->name('institucion.ocultarPortada');

    Route::post('/institucion/perfil/actualizarPortada', [InstitucionController::class, 'actualizarPortada'])
    ->name('institucion.actualizarPortada');

    Route::post('/institucion/perfil/actualizarImagenPerfil', [InstitucionController::class, 'actualizarImagenPerfil'])
    ->name('institucion.actualizarImagenPerfil');

    // Ruta para mostrar los registros pendientes de publicar
    Route::get('institucion/perfil/edit', [InstitucionController::class, 'perfiledit'])
    ->name('institucion.edit');

    Route::post('/institucion/perfil/eliminarPortada', [InstitucionController::class, 'eliminarPortada'])
    ->name('institucion.eliminarPortada');

    Route::post('/institucion/perfil/eliminarImagenPerfil', [InstitucionController::class, 'eliminarImagenPerfil'])
    ->name('institucion.eliminarImagenPerfil');

    Route::patch('institucion/perfil', [InstitucionController::class, 'perfilupdate'])
    ->name('institucion.perfilupdate');    


    Route::get('institucion/asesores', [InstitucionController::class, 'asesores'])
    ->name('institucion.asesores');

    Route::get('institucion/asesores/solicitudes', [InstitucionController::class, 'solicitudasesores'])
    ->name('institucion.solicitudasesores');

    Route::get('institucion/asesores/solicitudes/{asesor}', [InstitucionController::class, 'showsolicitudasesores'])
    ->name('institucion.showsolicitudasesores');

    Route::post('institucion/asesores/solicitudes/{asesor}/aprobarcuenta', [InstitucionController::class, 'aprobarsolicitud'])
    ->name('institucion.aprobarsolicitud');

    Route::post('institucion/asesores/solicitudes/{asesor}/rechazarcuenta', [InstitucionController::class, 'rechazarsolicitud'])
    ->name('institucion.rechazarsolicitud');
    
//------------------------------------------------------------------------------------|

//------------------------------------------------------------------------------------> Resgistro asesor

Route::get('asesor/validarcuenta', [AsesorController::class, 'validarcuenta'])
->name('asesor.validarcuenta');

Route::get('asesor/validarcuenta/{asesor}', [AsesorController::class, 'showvalidarcuenta'])
->name('asesor.showvalidarcuenta');


Route::post('asesor/validarcuenta/{asesor}/aprobarcuenta', [AsesorController::class, 'aprobarcuenta'])
->name('asesor.aprobarcuenta');

Route::post('asesor/validarcuenta/{asesor}/rechazarcuenta', [AsesorController::class, 'rechazarcuenta'])
->name('asesor.rechazarcuenta');


Route::get('asesor', [AsesorController::class, 'listadoasesores'])
->name('asesor.listadoasesores');

Route::get('asesor/solicitud', [AsesorController::class, 'solicitudasesores'])
->name('asesor.solicitudasesores');

Route::get('asesor/solicitud/{asesor}', [AsesorController::class, 'showsolicitudasesores'])
->name('asesor.showsolicitudasesores');

Route::post('asesor/solicitudes/{asesor}/aprobarcuenta', [AsesorController::class, 'aprobarsolicitud'])
->name('asesor.aprobarsolicitud');

Route::post('asesor/solicitudes/{asesor}/rechazarcuenta', [AsesorController::class, 'rechazarsolicitud'])
->name('asesor.rechazarsolicitud');


Route::post('asesor/institucion/editar', [AsesorController::class, 'editarinstitucion'])
->name('asesor.editarinstitucion');

//------------------------------------------------------------------------------------|


//------------------------------------------------------------------------------------> Perfil asesor

Route::get('asesor/perfil', [AsesorController::class, 'perfil'])
->name('asesor.perfil');

Route::post('/asesor/perfil/ocultarContacto', [AsesorController::class, 'ocultarContacto'])
->name('asesor.ocultarContacto');

Route::post('/asesor/perfil/actualizarCredencial', [AsesorController::class, 'actualizarCredencial'])
->name('asesor.actualizarCredencial');

Route::post('/asesor/perfil/actualizarImagenPerfil', [AsesorController::class, 'actualizarImagenPerfil'])
->name('asesor.actualizarImagenPerfil');

// Ruta para mostrar los registros pendientes de publicar
Route::get('asesor/perfil/edit', [AsesorController::class, 'perfiledit'])
->name('asesor.edit');

Route::post('/asesor/perfil/eliminarImagenPerfil', [AsesorController::class, 'eliminarImagenPerfil'])
->name('asesor.eliminarImagenPerfil');

Route::patch('asesor/perfil', [AsesorController::class, 'perfilupdate'])
->name('asesor.perfilupdate');



Route::get('asesor/institucion/vincular', [AsesorController::class, 'vincularinstitucion'])
->name('asesor.vincularinstitucion');  

Route::post('asesor/institucion/vincular/busqueda', [AsesorController::class, 'vincularinstitucionbusqueda'])
->name('asesor.vincularinstitucionbusqueda');  

Route::post('asesor/institucion/vincular', [AsesorController::class, 'vincularinstitucionstore'])
->name('asesor.vincularinstitucionstore');  


Route::delete('asesor/institucion/solicitud/{asesorinstitucionsolicitud}/cancelar', [AsesorController::class, 'cancelarsolicitudinstitucion'])
->name('asesor.cancelarsolicitudinstitucion');  

Route::delete('asesor/institucion/desvincular/{asesor}', [AsesorController::class, 'desvincularinstitucion'])
->name('asesor.desvincularinstitucion');  

//------------------------------------------------------------------------------------|


//------------------------------------------------------------------------------------> Perfil juez

Route::get('juez/perfil', [JuezController::class, 'perfil'])
->name('juez.perfil');

//------------------------------------------------------------------------------------|

}); //--------------------------------------------------------------------------------------------> Fin Middleware


//------------------------------------------------------------------------------------> Competencias

// Ruta para mostrar los registros anteriores
Route::get('competencia/historial', [CompetenciaController::class, 'previous'])
->name('competencia.previous');

// Ruta para mostrar los detalles de las competencias previas
Route::get('competencia/historial/{competencia}', [CompetenciaController::class, 'showprevious'])
->name('competencia.showprevious');

Route::resource('competencia', CompetenciaController::class)->parameters([
    'competencia' => 'competencia', //Corregir error {competencium} en -> php artisan route:list
]);
//->except(['destroy']); // Dejar fuera la ruta Destroy para agregarla manualmente 

//------------------------------------------------------------------------------------> CompetenciaCategoria

// Ruta para mostrar los detalles de registros no publicados
Route::get('competencia/{competencia}/categoria/{competenciaCategoria}', [CompetenciaCategoriaController::class, 'show'])
->name('competenciacategoria.show');


//------------------------------------------------------------------------------------|

/*Route::resource('competenciacategoria', CompetenciaCategoriaController::class)->parameters([
    'competenciacategoria' => 'competenciacategoria', //Corregir error {competencium} en -> php artisan route:list
]);*/


//------------------------------------------------------------------------------------> Competencias

Route::get('institucion', [InstitucionController::class, 'index'])
->name('institucion.index');

Route::get('institucion/signup', [InstitucionController::class, 'create'])
->name('institucion.create');

/*Route::post('institucion/store/{valores}', [InstitucionController::class, 'store'])
->name('institucion.store');*/

Route::get('institucion/reset', [InstitucionController::class, 'reset'])
->name('institucion.reset');

Route::get('institucion/anterior/{valores}', [InstitucionController::class, 'anterior'])
->name('institucion.anterior');

Route::post('institucion', [InstitucionController::class, 'store'])
->name('institucion.store');

Route::get('institucion/{institucion}', [InstitucionController::class, 'show'])
->name('institucion.show');



/*Route::get('institucion/originalcreate', [InstitucionController::class, 'originalCreate'])
->name('institucion.originalCreate');*7


/*Route::get('/formulario-multistep', [InstitucionController::class, 'index'])
->name('form.multistep');

Route::post('/formulario-multistep', [InstitucionController::class, 'process'])
->name('form.process');*/

/*Route::resource('institucion', InstitucionController::class)
->except(['create']);*/
//->except(['create', 'store']);


//------------------------------------------------------------------------------------| validarCredencial

Route::get('asesor/validarcredencial', [AsesorController::class, 'validarcredencial'])
->name('asesor.validarcredencial');

Route::post('asesor/validarcredencial', [AsesorController::class, 'validarcredencialstore'])
->name('asesor.validarcredencialstore');


Route::post('asesor/revisarcredencial', [AsesorController::class, 'revisarcredencialmanualmente'])
->name('asesor.revisarcredencialmanualmente');

Route::post('asesor/pruebaSweet', [AsesorController::class, 'pruebaSweet'])
->name('asesor.pruebaSweet');



Route::get('asesor/buscarcuenta', [AsesorController::class, 'buscarcuenta'])
->name('asesor.buscarcuenta');

Route::post('asesor/buscarcuenta', [AsesorController::class, 'buscarcuentastore'])
->name('asesor.buscarcuentastore');


Route::get('asesor/validarcredencial/{codigo_rechazo}', [AsesorController::class, 'validarcredencialrechazada'])
->name('asesor.validarcredencialrechazada');

Route::post('asesor/validarcredencial/{codigo_rechazo}', [AsesorController::class, 'validarcredencialrechazadastore'])
->name('asesor.validarcredencialrechazadastore');


Route::post('asesor/revisarcredencial/{codigo_rechazo}', [AsesorController::class, 'revisarcredencialrechazadamanualmente'])
->name('asesor.revisarcredencialrechazadamanualmente'); // PENDIENTE



Route::resource('asesor', AsesorController::class)->except(['index', 'edit']);



Route::resource('horario', HorarioController::class);

// Ruta para mostrar los detalles de las competencias previas
Route::get('competencia/{competencia}/agenda', [CompetenciaController::class, 'agenda'])
->name('competencia.agenda');


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
    session()->forget('form');
    
    return view('type-register');
})->name('type-register');
