<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::with(['empresa', 'sede'])->get();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresas = Empresa::where('estado', true)->get();
        $sedes = Sede::where('estado', true)->get();
        return view('usuarios.create', compact('empresas', 'sedes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'empresa_id' => 'nullable|exists:empresas,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'tipo_rol' => 'required|in:admin,tecnico,coordinador,operario,cliente',
            'cliente_id' => 'nullable|exists:clientes,id',
            'telefono' => 'nullable|string|max:20',
            'estado' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['estado'] = $validated['estado'] ?? true;

        User::create($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        $empresas = Empresa::where('estado', true)->get();
        $sedes = Sede::where('estado', true)->get();
        return view('usuarios.edit', compact('usuario', 'empresas', 'sedes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $usuario->id . '|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'empresa_id' => 'nullable|exists:empresas,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'tipo_rol' => 'required|in:admin,tecnico,coordinador,operario,cliente',
            'cliente_id' => 'nullable|exists:clientes,id',
            'telefono' => 'nullable|string|max:20',
            'estado' => 'boolean',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $usuario->update($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('seguridad.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}
