<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * CategoriaController
 * 
 * Gestiona el CRUD de categorías parametrizables para tipos de equipos.
 * Permite a los administradores crear, editar y eliminar categorías.
 */
class CategoriaController extends Controller
{
    /**
     * Listar todas las categorías
     */
    public function index(): View
    {
        $categorias = Categoria::with('tiposEquipos')
            ->orderBy('nombre')
            ->get();

        return view('categorias.index', compact('categorias'));
    }

    /**
     * Formulario para crear nueva categoría
     */
    public function create(): View
    {
        return view('categorias.create');
    }

    /**
     * Guardar nueva categoría
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:500',
            'icono' => 'nullable|string|max:50',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'estado' => 'boolean',
        ], [
            'nombre.unique' => 'Esta categoría ya existe',
            'color.regex' => 'El color debe ser un código hexadecimal válido (ej: #3b82f6)',
        ]);

        // Generar slug automáticamente
        $validated['slug'] = Categoria::generarSlug($validated['nombre']);
        $validated['color'] = $validated['color'] ?? '#3b82f6';

        Categoria::create($validated);

        return redirect()->route('parametros.categorias.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    /**
     * Mostrar detalles de una categoría
     */
    public function show(Categoria $categoria): View
    {
        $categoria->load('tiposEquipos');

        return view('categorias.show', compact('categoria'));
    }

    /**
     * Formulario para editar categoría
     */
    public function edit(Categoria $categoria): View
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Actualizar categoría
     */
    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string|max:500',
            'icono' => 'nullable|string|max:50',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'estado' => 'boolean',
        ], [
            'nombre.unique' => 'Esta categoría ya existe',
            'color.regex' => 'El color debe ser un código hexadecimal válido (ej: #3b82f6)',
        ]);

        // Actualizar slug si cambió el nombre
        if ($validated['nombre'] !== $categoria->nombre) {
            $validated['slug'] = Categoria::generarSlug($validated['nombre']);
        }

        $categoria->update($validated);

        return redirect()->route('parametros.categorias.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    /**
     * Eliminar categoría
     * 
     * Solo permite eliminar categorías sin tipos de equipos asociados
     */
    public function destroy(Categoria $categoria): RedirectResponse
    {
        if ($categoria->tiposEquipos()->count() > 0) {
            return back()->withErrors([
                'error' => 'No se puede eliminar una categoría que tiene tipos de equipos asociados'
            ]);
        }

        $categoria->delete();

        return redirect()->route('parametros.categorias.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }
}
