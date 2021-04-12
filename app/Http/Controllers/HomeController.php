<?php

namespace App\Http\Controllers;

use App\Models\Anexo1;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('seminarios');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect(route('presentaciones.inicio'));
    }

    public function seminarios(){
        $presentaciones = Anexo1::whereHas('estado', function($q){
            $q->where('nombre', 'Aprobado');
        })->with('anexos2', function($q2){
            $q2->whereHas('estado', function($q3){
                $q3->where('nombre', 'Aprobado');
            });
        })->get();

        return view('seminarios_anteriores', compact('presentaciones'));
    }
}
