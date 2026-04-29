<?php

namespace App\Http\Controllers\Administrativo;

use App\Models\Departamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * DepartamentoController - Módulo Administrativo
 * Gestión de departamentos/provincias
 * Ruta: /administrativo/departamentos
 */
class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentos = Departamento::with('pais')->get();
        return view('administrativo.departamentos.index', compact('departamentos'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Departamento $departamento)
    {
        $departamento->load(['pais', 'municipios']);
        return view('administrativo.departamentos.show', compact('departamento'));
    }
}
