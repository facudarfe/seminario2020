<?php

use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PresentacionesController;
use App\Http\Controllers\ValidacionesController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
    Route::get('usuarios/{user}/editar', [UserController::class, 'edit'])->name('usuarios.editar');
    Route::put('usuarios/{user}', [UserController::class, 'update'])->name('usuarios.actualizar');
    Route::delete('usuarios/eliminar', [UserController::class, 'destroy'])->name('usuarios.eliminar');

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
    ->middleware('permission:asignar.evaluador');
    Route::post('presentaciones/{version}/corregir', [PresentacionesController::class, 'corregirVersion'])->name('presentaciones.corregir')
    ->middleware('permission:presentaciones.corregir');
});

//Rutas para validaciones AJAX
Route::post('validar/{campo}', [ValidacionesController::class, 'validarDNI'])->name('validar.DNI');


