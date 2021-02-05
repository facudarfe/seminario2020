<?php

namespace App\Http\Controllers;

use App\Mail\ContactoMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function index(){
        return view('contacto');
    }

    public function send(Request $request){
        $request->validate([
            'asunto' => ['required', 'max:30'],
            'descripcion' => ['required', 'max:500']
        ]);

        $users = User::role('Administrador')->get();
        Mail::to($users)->send(new ContactoMail($request));

        return redirect(route('contacto.inicio'))->with('exito', 'Se ha enviado su consulta correctamente.');
    }
}
