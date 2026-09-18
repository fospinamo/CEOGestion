<?php

namespace App\Http\Controllers\Administrativo;

use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;

/**
 * MunicipioController - Módulo Administrativo
 * Gestión de municipios/ciudades
 * Ruta: /administrativo/municipios
 */
class MunicipioController extends Controller
{
    use PermissionCheckTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkPermission('municipios.ver');
        $municipios = Municipio::with('departamento.pais')->get();
        return view('administrativo.municipios.index', compact('municipios'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipio $municipio)
    {
        $this->checkPermission('municipios.ver');
        $municipio->load(['departamento.pais', 'barrios', 'sedes']);
        return view('administrativo.municipios.show', compact('municipio'));
    }
}
