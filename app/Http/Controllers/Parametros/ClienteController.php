<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

/**
 * ClienteController - Módulo Parámetros
 * Gestión de clientes del sistema
 * Ruta: /parametros/clientes
 */
class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::with(['empresa', 'ciudadNotificacion'])
            ->get();

        return view('parametros.clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        $cliente = null;
        $empresas = Empresa::orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();

        return view('parametros.clientes.create', compact('cliente', 'empresas', 'municipios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'tipo_documento' => 'required|in:NIT,CC,CE,PASAPORTE',
            'documento' => 'required|string|unique:clientes,documento',
            'digito_verificacion' => 'nullable|string|size:1',
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'primer_nombre' => 'nullable|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'nullable|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'email_principal' => 'required|email|unique:clientes,email_principal',
            'email_secundario' => 'nullable|email',
            'telefono_fijo' => 'nullable|string|max:20',
            'telefono_movil' => 'nullable|string|max:20',
            'telefono_whatsapp' => 'nullable|string|max:20',
            'direccion_notificacion' => 'required|string|max:500',
            'ciudad_notificacion_id' => 'required|exists:municipios,id',
            'contacto_nombre' => 'required|string|max:255',
            'contacto_cargo' => 'nullable|string|max:100',
            'contacto_telefono' => 'required|string|max:20',
            'contacto_email' => 'required|email',
        ]);

        Cliente::create($validated);

        return redirect()->route('parametros.clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load(['empresa', 'ciudadNotificacion.departamento.pais', 'contratos', 'sedes']);

        return view('parametros.clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();

        return view('parametros.clientes.edit', compact('cliente', 'empresas', 'municipios'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'tipo_documento' => 'required|in:NIT,CC,CE,PASAPORTE',
            'documento' => 'required|string|unique:clientes,documento,' . $cliente->id,
            'digito_verificacion' => 'nullable|string|size:1',
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'primer_nombre' => 'nullable|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'nullable|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'email_principal' => 'required|email|unique:clientes,email_principal,' . $cliente->id,
            'email_secundario' => 'nullable|email',
            'telefono_fijo' => 'nullable|string|max:20',
            'telefono_movil' => 'nullable|string|max:20',
            'telefono_whatsapp' => 'nullable|string|max:20',
            'direccion_notificacion' => 'required|string|max:500',
            'ciudad_notificacion_id' => 'required|exists:municipios,id',
            'contacto_nombre' => 'required|string|max:255',
            'contacto_cargo' => 'nullable|string|max:100',
            'contacto_telefono' => 'required|string|max:20',
            'contacto_email' => 'required|email',
        ]);

        $cliente->update($validated);

        return redirect()->route('parametros.clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('parametros.clientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }
}
