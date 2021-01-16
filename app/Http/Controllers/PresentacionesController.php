<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresentacionesController extends Controller
{
    public function index(){
        return view('presentaciones.inicio');
    }
}
