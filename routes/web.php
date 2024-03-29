<?php

use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Anexo2Controller;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AyudaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasantiasController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PresentacionesController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\TemasController;
use App\Http\Controllers\ValidacionesController;
use App\Mail\RegistroMail;
use App\Models\Anexo2;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login-post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => 'guest'], function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'update'])->name('password.update');
});

// Rutas muestra de seminarios aprobados
Route::get('/seminarios', [HomeController::class, 'seminarios'])->name('seminarios');
Route::get('/seminarios/{anexo2}/descargarInforme', [PDFController::class, 'descargarInformeFinal'])->name('seminarios.informe');
Route::get('/seminarios/{presentacion}/descargarCodigoFuente', [PDFController::class, 'descargarCodigoFuente'])->name('seminarios.informe');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('inicio');

    //Rutas usuario
    Route::prefix('usuarios')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('usuarios.inicio')
        ->middleware('permission:usuarios.ver');
        Route::get('/crear', [UserController::class, 'create'])->name('usuarios.crear')
            ->middleware('permission:usuarios.crear');
        Route::post('/', [UserController::class, 'store'])->name('usuarios.almacenar')
            ->middleware('permission:usuarios.crear');
        Route::get('/{user}/editar', [UserController::class, 'edit'])->name('usuarios.editar')
            ->middleware(['permission:usuarios.editar', 'can:gestionar,user']);
        Route::put('/{user}', [UserController::class, 'update'])->name('usuarios.actualizar');
            //->middleware(['permission:usuarios.editar', 'can:gestionar,user']);
        Route::delete('/eliminar', [UserController::class, 'destroy'])->name('usuarios.eliminar')
            ->middleware(['permission:usuarios.eliminar', 'can:gestionar,user']);
        Route::get('/editarPerfil', [UserController::class, 'editarPerfil'])->name('usuarios.editarPerfil');
        Route::post('/changePassword', [UserController::class, 'changePassword'])->name('usuarios.changePassword');
    });

    //Rutas roles y permisos
    Route::prefix('permisos')->group(function(){
        Route::get('/', [PermisoController::class, 'index'])->name('permisos.inicio')
        ->middleware('permission:permisos.ver');
        Route::post('/', [PermisoController::class, 'store'])->name('permisos.almacenar')
            ->middleware('permission:permisos.crear');
    });

    //Rutas cuerpo docente
    Route::group(['middleware' => 'permission:docentes.gestionar', 'prefix' => 'docentes'], function(){
        Route::get('/', [DocenteController::class, 'index'])->name('docentes.inicio');
        Route::get('/crear', [DocenteController::class, 'create'])->name('docentes.crear');
        Route::post('/', [DocenteController::class, 'store'])->name('docentes.almacenar');
        Route::get('/{docente}/editar', [DocenteController::class, 'edit'])->name('docentes.editar');
        Route::put('/{docente}', [DocenteController::class, 'update'])->name('docentes.actualizar');
        Route::delete('/{docente}/eliminar', [DocenteController::class, 'destroy'])->name('docentes.eliminar');
    });

    //Rutas presentaciones
    Route::prefix('presentaciones')->group(function(){
        Route::get('/', [PresentacionesController::class, 'index'])->name('presentaciones.inicio');
        Route::get('/crear', [PresentacionesController::class, 'create'])->name('presentaciones.crear')
            ->middleware('can:crear,App\Models\Anexo1');
        Route::post('/', [PresentacionesController::class, 'store'])->name('presentaciones.almacenar');
        Route::get('/{presentacion}/ver', [PresentacionesController::class, 'show'])->name('presentaciones.ver')
            ->middleware('can:mostrar,presentacion');
        Route::post('/{presentacion}/asignarEvaluador', [PresentacionesController::class, 'asignarEvaluador'])->name('presentaciones.asignarEvaluador')
            ->middleware('permission:presentaciones.asignar.evaluador');
        Route::post('/corregir', [PresentacionesController::class, 'corregirVersion'])->name('presentaciones.corregir')
            ->middleware('permission:presentaciones.corregir');
        Route::get('/{presentacion}/resubir', [PresentacionesController::class, 'resubir'])->name('presentaciones.resubir');
        Route::post('/{presentacion}/resubir', [PresentacionesController::class, 'resubirVersion'])->name('presentaciones.resubirVersion')
            ->middleware('can:resubirVersion,presentacion');
        Route::post('/{presentacion}/regularizar', [PresentacionesController::class, 'regularizarPresentacion'])->name('presentaciones.regularizar')
            ->middleware('permission:presentaciones.regularizar');
        Route::patch('/{presentacion}/solicitarContinuidad', [PresentacionesController::class, 'solicitarContinuidad'])->name('presentaciones.solicitarContinuidad')
        ->middleware('can:solicitarContinuidad,presentacion');
    
        //Rutas PDF presentaciones
        Route::get('/{presentacion}/{version}/PDF', [PDFController::class, 'generarAnexo1'])->name('pdf.anexo1')
            ->middleware('permission:anexos1.generarPDF');

        //Rutas almacenamiento
        Route::post('/{presentacion}/subirInforme', [StorageController::class, 'guardarInforme'])->name('presentaciones.subirInforme')
            ->middleware('can:subirInforme,presentacion');
        Route::get('/{presentacion}/descargarInforme', [StorageController::class, 'descargarInforme'])->name('presentaciones.descargarInforme');
        Route::patch('/{presentacion}/subirCodigoFuente', [StorageController::class, 'subirCodigoFuente'])->name('presentaciones.subirCodigoFuente')
            ->middleware('can:subirCodigoFuente,presentacion');

        //Rutas para aceptar o rechazar la participacion de otro estudiante en la presentacion
        Route::patch('/{user}/{presentacion}/aceptarORechazar', [PresentacionesController::class, 'aceptarORechazar'])
            ->name('presentaciones.aceptarORechazar')->middleware('can:aceptarORechazar,presentacion');

        //Rutas para proponer mesa examinadora
        Route::post('/{presentacion}/proponerFecha',[PresentacionesController::class, 'proponerFecha'])->name('presentaciones.proponerFecha')
            ->middleware('can:proponerFecha,presentacion');
    });

    //Rutas contacto
    Route::get('contacto', [ContactoController::class, 'index'])->name('contacto.inicio');
    Route::post('contacto', [ContactoController::class, 'send'])->name('contacto.enviar');

    //Rutas propuestas de temas
    Route::prefix('temas')->group(function(){
        Route::get('/', [TemasController::class, 'index'])->name('temas.inicio');
        Route::get('/{tema}/ver', [TemasController::class, 'show'])->name('temas.ver');
        Route::get('/crear', [TemasController::class, 'create'])->name('temas.crear_editar')->middleware('permission:propuestas.temas.crear');
        Route::post('/', [TemasController::class, 'store'])->name('temas.subir')->middleware('permission:propuestas.temas.crear');
        Route::get('/{tema}/editar', [TemasController::class, 'edit'])->name('temas.editar')->middleware('can:manipular,tema');
        Route::put('/{tema}', [TemasController::class, 'update'])->name('temas.actualizar')->middleware('can:manipular,tema');
        Route::delete('/{tema}/eliminar', [TemasController::class, 'destroy'])->name('temas.eliminar')->middleware('can:manipular,tema');
        Route::get('/{tema}/solicitar', [TemasController::class, 'request'])->name('temas.solicitar')
        ->middleware('permission:propuestas.temas.solicitar', 'can:solicitar,tema');
        Route::get('/{tema}/liberar', [TemasController::class, 'free'])->name('temas.liberar')->middleware('can:liberar,tema');
        Route::get('/{tema}/crearPresentacion', [TemasController::class, 'createPresentation'])->name('temas.crearPresentacion')
            ->middleware('can:crearPresentacion,tema');
    });

    //Rutas propuestas de pasantias
    Route::prefix('pasantias')->group(function(){
        Route::get('/', [PasantiasController::class, 'index'])->name('pasantias.inicio');
        Route::get('/crear', [PasantiasController::class, 'create'])->name('pasantias.crear_editar')->middleware('permission:propuestas.pasantias.crear');
        Route::post('/', [PasantiasController::class, 'store'])->name('pasantias.subir')->middleware('permission:propuestas.pasantias.crear');
        Route::get('/{pasantia}/ver', [PasantiasController::class, 'show'])->name('pasantias.ver');
        Route::post('/{pasantia}/solicitar', [PasantiasController::class, 'request'])->name('pasantias.solicitar')
            ->middleware('permission:propuestas.pasantias.solicitar', 'can:solicitar,pasantia');
        Route::get('/{pasantia}/liberar', [PasantiasController::class, 'free'])->name('pasantias.liberar')->middleware('can:liberar,pasantia');
        Route::get('/{pasantia}/editar', [PasantiasController::class, 'edit'])->name('pasantias.editar')->middleware('can:manipular,pasantia');
        Route::put('/{pasantia}', [PasantiasController::class, 'update'])->name('pasantias.actualizar')->middleware('can:manipular,pasantia');
        Route::delete('/{pasantia}/eliminar', [PasantiasController::class, 'destroy'])->name('pasantias.eliminar')->middleware('can:manipular,pasantia');
        Route::patch('/{pasantia}/habilitar', [PasantiasController::class, 'enable'])->name('pasantias.habilitar')->middleware('can:manipular,pasantia');
        Route::patch('/{pasantia}/deshabilitar', [PasantiasController::class, 'disable'])->name('pasantias.deshabilitar')->middleware('can:manipular,pasantia');
        Route::get('/{pasantia}/PDF', [PDFController::class, 'generarPDFPasantia'])->name('pasantias.generarPDF')->middleware('permission:propuestas.pasantias.generarPDF');
    });

    //Rutas Anexos 2
    Route::prefix('anexos2')->group(function () {
        Route::get('/{anexo2}/PDF', [PDFController::class, 'generarAnexo2'])->name('anexos2.PDF')->middleware('can:generarPDF,anexo2');
        Route::post('/{anexo2}/definirFechaYTribunal', [Anexo2Controller::class, 'definirFechaYTribunal'])
            ->middleware('permission:anexos2.definirFechaYTribunal');
        Route::get('/{anexo2}/ver', [Anexo2Controller::class, 'show'])->name('anexo2.ver')->middleware('can:ver,anexo2');
        Route::patch('/{anexo2}/evaluarExamen', [Anexo2Controller::class, 'evaluarExamen'])->name('anexo2.evaluarExamen')
            ->middleware('permission:anexos2.evaluar');
    });

    // Manual de usuarios
    Route::get('/ayuda', [AyudaController::class, 'index'])->name('ayuda');
});

//Rutas para obtener informe final y codigo fuente de proyecto (tambien se utiliza para usuarios no registrados que quieran ver)
Route::get('/anexos2/{anexo2}/descargarInforme', [StorageController::class, 'descargarInformeFinal'])->name('anexos2.descargarInforme');
Route::get('/presentaciones/{presentacion}/descargarCodigoFuente', [StorageController::class, 'descargarCodigoFuente'])
    ->name('presentaciones.descargarCodigoFuente');

//Rutas para validaciones AJAX
Route::post('verificarPassword', [ValidacionesController::class, 'verificarPassword'])->name('verificarPassword');
Route::post('verificar/{campo}', [ValidacionesController::class, 'verificarCampo'])->name('verificarCampo');

