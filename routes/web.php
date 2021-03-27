<?php

use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasantiasController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PresentacionesController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\TemasController;
use App\Http\Controllers\ValidacionesController;
use App\Mail\RegistroMail;
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
    
        //Rutas PDF presentaciones
        Route::get('/{presentacion}/{version}/PDF', [PDFController::class, 'generarAnexo1'])->name('pdf.anexo1')
            ->middleware('permission:generar.pdf.anexo1');

        //Rutas almacenamiento
        Route::post('/{presentacion}/subirInforme', [StorageController::class, 'guardarInforme'])->name('presentaciones.subirInforme')
            ->middleware('can:subirInforme,presentacion');
        Route::get('/{presentacion}/descargarInforme', [StorageController::class, 'descargarInforme'])->name('presentaciones.descargarInforme');
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
        /*Route::get('/{tema}/editar', [TemasController::class, 'edit'])->name('propuestas.editar')->middleware('can:manipular,tema');
        Route::put('/{tema}', [TemasController::class, 'update'])->name('propuestas.actualizar')->middleware('can:manipular,tema');
        Route::delete('/{tema}/eliminar', [TemasController::class, 'destroy'])->name('propuestas.eliminar')->middleware('can:manipular,tema');
        Route::get('/{tema}/solicitar', [TemasController::class, 'request'])->name('propuestas.solicitar')
        ->middleware('permission:propuestas.temas.solicitar', 'can:solicitar,tema');
        Route::get('/{tema}/liberar', [TemasController::class, 'free'])->name('propuestas.liberar')->middleware('can:liberar,tema');
        Route::get('/{tema}/crearPresentacion', [TemasController::class, 'createPresentation'])->name('temas.crearPresentacion')
            ->middleware('can:crearPresentacion,tema');*/
    });
});

//Rutas para validaciones AJAX
Route::post('verificarPassword', [ValidacionesController::class, 'verificarPassword']);
Route::post('verificar/{campo}', [ValidacionesController::class, 'verificarCampo']);

