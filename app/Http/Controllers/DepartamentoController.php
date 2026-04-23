<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentos = Departamento::with('pais')->get();
        return view('departamentos.index', compact('departamentos'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Departamento $departamento)
    {
        $departamento->load(['pais', 'municipios']);
        return view('departamentos.show', compact('departamento'));
    }
}
