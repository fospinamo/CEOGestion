<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $municipios = Municipio::with('departamento.pais')->get();
        return view('municipios.index', compact('municipios'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipio $municipio)
    {
        $municipio->load(['departamento.pais', 'barrios', 'sedes']);
        return view('municipios.show', compact('municipio'));
    }
}
