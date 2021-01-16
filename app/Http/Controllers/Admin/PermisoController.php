<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisoController extends Controller
{
    public function index(){
        $roles = Role::all()->where('name', '<>', 'Administrador');
        $permisos = Permission::orderBy('name', 'asc')->get()->pluck('name');
        return view('admin.permisos.inicio', compact('roles', 'permisos'));
    }

    public function store(Request $request){
        //Sincronizacion permisos Docente responsable
        $rol = Role::find(2);
        $rol->syncPermissions($request->input('permisos2'));

        //Sincronizacion permisos Docente colaborador
        $rol = Role::find(3);
        $rol->syncPermissions($request->input('permisos3'));

        //Sincronizacion permisos Estudiante
        $rol = Role::find(4);
        $rol->syncPermissions($request->input('permisos4'));

        return redirect(route('permisos.inicio'))->with('exito', 'Se han actualizado los permisos de los roles con éxito.');
    }
}
