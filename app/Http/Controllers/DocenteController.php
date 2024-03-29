<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docentes = Docente::all();
        return view('admin.docentes.inicio', compact('docentes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $docente = new Docente();
        return view('admin.docentes.crear_editar', compact('docente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni' => ['required', 'unique:docentes,dni', 'numeric', 'min:1000000'],
            'name' => ['required', 'max:255'],
            'email' => [Rule::requiredIf($request->has('esDocente')), 'unique:docentes,email'],
        ]);

        $docente = new Docente();
        $docente->dni = $request->dni;
        $docente->name = $request->name;
        $docente->email = $request->email;
        if($request->has('esDocente'))
            $docente->esDocente = 1;
        $docente->save();

        return redirect()->route('docentes.inicio')->with('exito', 'Se ha creado el docente con éxito');
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
    public function edit(Docente $docente)
    {
        return view('admin.docentes.crear_editar', compact('docente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'dni' => ['required', Rule::unique('docentes')->ignore($docente->dni, 'dni'), 'numeric', 'min:1000000'],
            'name' => ['required', 'max:255'],
            'email' => [Rule::requiredIf($request->has('esDocente')), Rule::unique('docentes')->ignore($docente->dni, 'dni')],
        ]);

        $docente->dni = $request->dni;
        $docente->name = $request->name;
        $docente->email = $request->email;
        if($request->has('esDocente'))
            $docente->esDocente = 1;
        else
            $docente->esDocente = 0;
        $docente->save();

        return redirect()->route('docentes.inicio')->with('exito', 'Se ha actualizado el docente con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Docente $docente)
    {
        $docente->delete();

        return redirect()->route('docentes.inicio')->with('exito', 'Se ha eliminado el docente con éxito');
    }
}
