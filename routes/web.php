<?php

use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PresentacionesController;
use App\Http\Controllers\ValidacionesController;
use App\Mail\RegistroMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('inicio');
    })->name('inicio');

    //Rutas usuario
    Route::get('usuarios', [UserController::class, 'index'])->name('usuarios.inicio')->middleware('permission:usuarios.ver');
    Route::get('usuarios/crear', [UserController::class, 'create'])->name('usuarios.crear')->middleware('permission:usuarios.crear');
    Route::post('usuarios', [UserController::class, 'store'])->name('usuarios.almacenar')->middleware('permission:usuarios.crear');
    Route::get('usuarios/{user}/editar', [UserController::class, 'edit'])->name('usuarios.editar')->middleware('permission:usuarios.editar');
    Route::put('usuarios/{user}', [UserController::class, 'update'])->name('usuarios.actualizar')->middleware('permission:usuarios.editar');
    Route::delete('usuarios/eliminar', [UserController::class, 'destroy'])->name('usuarios.eliminar')->middleware('permission:usuarios.eliminar');

    //Rutas roles y permisos
    Route::get('permisos', [PermisoController::class, 'index'])->name('permisos.inicio')->middleware('permission:permisos.ver');
    Route::post('permisos', [PermisoController::class, 'store'])->name('permisos.almacenar')->middleware('permission:permisos.crear');

    //Rutas presentaciones
    Route::get('presentaciones', [PresentacionesController::class, 'index'])->name('presentaciones.inicio');
    Route::get('presentaciones/crear', [PresentacionesController::class, 'create'])->name('presentaciones.crear')
    ->middleware('permission:presentaciones.crear');
    Route::post('presentaciones', [PresentacionesController::class, 'store'])->name('presentaciones.almacenar')
    ->middleware('permission:presentaciones.crear');
    Route::get('presentaciones/{presentacion}/ver', [PresentacionesController::class, 'show'])->name('presentaciones.ver');
    Route::post('presentaciones/{presentacion}/asignarEvaluador', [PresentacionesController::class, 'asignarEvaluador'])->name('presentaciones.asignarEvaluador')
    ->middleware('permission:presentaciones.asignar.evaluador');
    Route::post('presentaciones/corregir', [PresentacionesController::class, 'corregirVersion'])->name('presentaciones.corregir')
    ->middleware('permission:presentaciones.corregir');
    Route::get('presentaciones/{presentacion}/resubir', [PresentacionesController::class, 'resubir'])->name('presentaciones.resubir');
    Route::post('presentaciones/{presentacion}/resubir', [PresentacionesController::class, 'resubirVersion'])->name('presentaciones.resubirVersion');
    Route::post('presentaciones/{presentacion}/regularizar', [PresentacionesController::class, 'regularizarPresentacion'])->name('presentaciones.regularizar')
    ->middleware('permission:presentaciones.regularizar');

    //Rutas PDF presentaciones
    Route::get('presentaciones/{presentacion}/{version}/PDF', [PDFController::class, 'generarAnexo1'])->name('pdf.anexo1')
    ->middleware('permission:generar.pdf.anexo1');

    //Rutas contacto
    Route::get('contacto', [ContactoController::class, 'index'])->name('contacto.inicio');
    Route::post('contacto', [ContactoController::class, 'send'])->name('contacto.enviar');
});

//Rutas para validaciones AJAX
Route::post('validar/{campo}', [ValidacionesController::class, 'validarDNI'])->name('validar.DNI');

Route::get('prueba-pdf', function(){
    $pdf = app('dompdf.wrapper');

    $pdf->loadView('PDF.prueba');

    return $pdf->stream('archivo.pdf');
});

