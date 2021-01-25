<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ValidacionesController extends Controller
{
    public function validarDNI(Request $request, $campo){
        $user = User::where($campo, $request->$campo)->get();

        if($user->count()){
            return response()->json(false);
        }
        else{
            return response()->json(true);
        }
    }
}
