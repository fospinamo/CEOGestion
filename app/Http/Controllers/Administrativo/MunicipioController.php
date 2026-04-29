<?php

namespace App\Http\Controllers\Administrativo;

use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * MunicipioController - Módulo Administrativo
 * Gestión de municipios/ciudades
 * Ruta: /administrativo/municipios
 */
class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $municipios = Municipio::with('departamento.pais')->get();
        return view('administrativo.municipios.index', compact('municipios'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipio $municipio)
    {
        $municipio->load(['departamento.pais', 'barrios', 'sedes']);
        return view('administrativo.municipios.show', compact('municipio'));
    }
}
