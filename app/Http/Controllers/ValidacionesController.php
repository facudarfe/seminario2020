<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ValidacionesController extends Controller
{
    public function verificarCampo(Request $request, $campo){
        //Para reutilizar la misma validacion para el crear y editar usuario se va a preguntar que operacion se esta realizando
        //En el caso del editar se excluye al mismo usuario para que no valide contra sus propios campos

        $editingUser = User::find($request->id); //Que usuario se esta editando
        if($request->operacion == 'creando')
            $user = User::where($campo, $request->$campo)->get();
        else
            $user = User::where($campo, $request->$campo)->where('id', '!=', $editingUser->id)->get();

        if($user->count()){
            return response()->json(false);
        }
        else{
            return response()->json(true);
        }
    }

    public function verificarPassword(Request $request){
        if(Hash::check($request->input('oldpassword'), auth()->user()->password)){
            return response()->json(true);
        }
        else{
            return response()->json(false);
        }
    }
}
