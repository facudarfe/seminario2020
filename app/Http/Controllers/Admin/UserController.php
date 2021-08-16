<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsuarioRequest;
use App\Mail\RegistroMail;
use App\Models\Docente;
use App\Models\User;
use App\Rules\ChequearPassword;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;
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
        $roles = auth()->user()->rolesPermitidos();
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
        $rol = Role::find($request->get('rol'));
        if($request->user()->can('manipularRol', $rol)){
            try{
                DB::transaction(function () use($request, $rol){
                    $user = User::create(['name' => $request->name, 
                    'lu' => $request->lu, 
                    'dni' => $request->dni, 
                    'email' => $request->email,
                    'password' => Hash::make($request->dni),
                    'direccion' => $request->direccion,
                    'telefono' => $request->telefono]);

                    $user->assignRole($rol->name);

                    $this->cargarDocente($user); //Cargar a usuario docente en la tabla de docentes

                    //Envio de mail
                    $mail = new RegistroMail($user->name, $rol->name);
                    Mail::to($user->email)->send($mail);
                });

                return redirect(route('usuarios.inicio'))->with('exito', 'El usuario ha sido creado con éxito.');
            }
            catch(Exception $e){
                return redirect(route('usuarios.inicio'))->withErrors('Ha ocurrido un error: ' . $e->getMessage());
            }   
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
        $rol = $user->roles->first();
        $roles = auth()->user()->rolesPermitidos();
        $admin = true; /*Para reutilizar la vista en el Editar Perfil se le pasa un valor booleano para chequear si se esta editando
                        desde el panel administrativo o desde el editar perfil*/
        
        return view('admin.usuarios.editar', compact('user', 'roles', 'admin'));
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
        /*Se va a controlar los permisos desde aca y no desde el middleware para poder reutilizar la ruta cuando se edita desde el
        panel administrativo y desde el 'Editar Perfil'*/
        if($request->user()->can('gestionar', $user) || $user->id == $request->user()->id){
            try{
                DB::transaction(function () use($request, $user){
                    $dniViejo = $user->dni; //Esto para mantener la redundancia en la tabla de docentes

                    $user->dni = $request->dni;
                    $user->name = $request->name;
                    $user->lu = $request->lu;
                    $user->email = $request->email;
                    if($request->user()->hasRole('Administrador')){
                        if(!empty($request->password)){
                            $user->password = Hash::make($request->password);
                            $user->cambio_contrasena = false;
                        }
                    }
                    
                    $user->direccion = $request->direccion;
                    $user->telefono = $request->telefono;
            
                    $rol = Role::find($request->get('rol')) ?? $user->roles->first(); //Si el campo rol no esta definido se le asigna el rol que ya tiene
                    $user->syncRoles($rol->name);
                    $user->save();

                    $this->editarDocente($user, $dniViejo);
                });

                return redirect()->back()->with('exito', 'El usuario se ha modificado exitosamente');
            }
            catch(Exception $e){
                return redirect()->back()->withErrors('Ha ocurrido un error: ' . $e->getMessage());
            }
        }
        else{
            abort(403, 'No tiene permiso de editar este usuario');
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
        $rol = $user->roles->first();
        $user->delete();
        return redirect(route('usuarios.inicio'))->with('exito', 'Se ha eliminado el usuario con exito');
    }

    public function editarPerfil(){
        $user = auth()->user();
        $admin = false; /*Para reutilizar la vista en el Editar Perfil se le pasa un valor booleano para chequear si se esta editando
                        desde el panel administrativo o desde el editar perfil*/

        return view('admin.usuarios.editar', compact('user', 'admin'));
    }

    public function changePassword(Request $request){
        $request->validate([
            'oldpassword' => ['required', new ChequearPassword],
            'newpassword' => ['required', 'min:7'],
            'repeatnewpassword' => ['required', 'same:newpassword'],
        ]);

        $user = User::find(auth()->user()->id);

        $user->password = Hash::make($request->newpassword);
        $user->cambio_contrasena = true;
        $user->save();

        return redirect()->back()->with('exito', 'La contraseña se actualizó con éxito.');
    }

    //Metodo para que cuando se cree un usuario de tipo docente este se cargue en la tabla de docentes
    private function cargarDocente(User $user){
        if($user->hasRole(['Docente responsable', 'Docente colaborador'])){
            $docente = Docente::find($user->dni);
            if(!$docente){
                $docente = new Docente();
                $docente->dni = $user->dni;
            }

            $docente->name = $user->name;
            $docente->email = $user->email;
            $docente->save();
        }
    }

    //Metodo para que cuando se edite un usuario de tipo docente este se edite en la tabla de docentes
    private function editarDocente(User $user, $dniViejo){
        $docente = Docente::find($dniViejo);
        if($docente){
            $docente->dni = $user->dni;
            $docente->name = $user->name;
            $docente->email = $user->email;
            $docente->save();
        }
    }
}
