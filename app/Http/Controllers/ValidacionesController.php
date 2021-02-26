<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ValidacionesController extends Controller
{
    public function verificarCampo(Request $request, $campo){
        $user = User::where($campo, $request->$campo)->get();

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
