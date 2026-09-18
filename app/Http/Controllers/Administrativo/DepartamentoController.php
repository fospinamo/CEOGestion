<?php

namespace App\Http\Controllers\Administrativo;

use App\Models\Departamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;

/**
 * DepartamentoController - Módulo Administrativo
 * Gestión de departamentos/provincias
 * Ruta: /administrativo/departamentos
 */
class DepartamentoController extends Controller
{
    use PermissionCheckTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkPermission('departamentos.ver');
        $departamentos = Departamento::with('pais')->get();
        return view('administrativo.departamentos.index', compact('departamentos'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Departamento $departamento)
    {
        $this->checkPermission('departamentos.ver');
        $departamento->load(['pais', 'municipios']);
        return view('administrativo.departamentos.show', compact('departamento'));
    }
}
