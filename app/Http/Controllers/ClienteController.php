<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * ClienteController
 * 
 * Gestiona operaciones CRUD para clientes del sistema TI.
 * Los clientes son empresas o personas que contratan servicios TI.
 */
class ClienteController extends Controller
{
    /**
     * Listar todos los clientes con paginación
     */
    public function index(): View
    {
        $clientes = Cliente::with(['empresa', 'ciudadNotificacion'])
            ->get();

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Formulario para crear nuevo cliente
     */
    public function create(): View
    {
        $cliente = null;
        $empresas = Empresa::orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();

        return view('clientes.create', compact('cliente', 'empresas', 'municipios'));
    }

    /**
     * Guardar nuevo cliente en la base de datos
     */
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

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    /**
     * Mostrar detalles de un cliente
     */
    public function show(Cliente $cliente): View
    {
        $cliente->load(['empresa', 'ciudadNotificacion.departamento.pais', 'contratos', 'sedes']);

        return view('clientes.show', compact('cliente'));
    }

    /**
     * Formulario para editar cliente
     */
    public function edit(Cliente $cliente): View
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();

        return view('clientes.edit', compact('cliente', 'empresas', 'municipios'));
    }

    /**
     * Actualizar cliente
     */
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
            'estado' => 'boolean',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente actualizado exitosamente');
    }

    /**
     * Eliminar cliente (soft delete)
     */
    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }
}
