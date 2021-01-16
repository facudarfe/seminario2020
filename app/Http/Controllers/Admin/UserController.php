<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.usuarios.inicio', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.usuarios.crear', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuarioRequest $request)
    {
        if($request->user()->can('usuarios.crear.' . $request->get('rol'))){
            $user = User::create(['name' => $request->name, 
            'lu' => $request->lu, 
            'dni' => $request->dni, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'direccion' => $request->direccion,
            'telefono' => $request->telefono]);

            $rol = Role::find($request->get('rol'));
            $user->assignRole($rol->name);
            
            return redirect(route('usuarios.inicio'))->with('exito', 'El usuario ha sido creado con éxito.');
        }
        else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if(Auth::user()->can('usuarios.editar.' . $user->roles->first()->id)){
            $roles = Role::all();
            return view('admin.usuarios.editar', compact('user', 'roles'));
        }
        else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsuarioRequest $request, User $user)
    {
        if($request->user()->can('usuarios.editar.' . $request->get('rol'))){
            $user->dni = $request->dni;
            $user->name = $request->name;
            $user->lu = $request->lu;
            $user->email = $request->email;
            if(!empty($request->password))
                $user->password = Hash::make($request->password);
            $user->direccion = $request->direccion;
            $user->telefono = $request->telefono;

            $rol = Role::find($request->get('rol'));
            $user->syncRoles($rol->name);
            $user->save();

            return redirect(route('usuarios.inicio'))->with('exito', 'El usuario se ha modificado exitosamente');
        }
        else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::find($request->user_id);
        if(Auth::user()->can('usuarios.eliminar.' . $user->roles->first()->id)){
            $user->delete();
            return redirect(route('usuarios.inicio'))->with('exito', 'Se ha eliminado el usuario con exito');
        }
        else{
            abort(403);
        }
    }
}
