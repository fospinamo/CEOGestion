<?php

namespace App\Http\Controllers;

use App\Models\DocumentoAdjunto;
use App\Models\Contrato;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

/**
 * DocumentoAdjuntoController
 * 
 * Gestiona documentos adjuntos asociados a contratos y servicios.
 * Permite cargar, descargar y eliminar archivos digitalizados.
 */
class DocumentoAdjuntoController extends Controller
{
    /**

     * Listar documentos de un contrato o servicio
     */
    public function index(Request $request): View
    {
        $query = DocumentoAdjunto::query();

        if ($request->has('entidad_type') && $request->has('entidad_id')) {
            $query->where('entidad_type', $request->entidad_type)
                  ->where('entidad_id', $request->entidad_id);
        }

        $documentos = $query->with('subidoPor')
            ->get();

        return view('documentos.index', compact('documentos'));
    }

    /**
     * Formulario para crear/cargar nuevo documento
     */
    public function create(Request $request): View
    {
        $entidad_type = $request->query('entidad_type');
        $entidad_id = $request->query('entidad_id');

        // Validar que la entidad existe
        if ($entidad_type === 'App\Models\Contrato') {
            $entidad = Contrato::findOrFail($entidad_id);
        } elseif ($entidad_type === 'App\Models\Servicio') {
            $entidad = Servicio::findOrFail($entidad_id);
        } else {
            abort(404, 'Entidad no válida');
        }

        return view('documentos.create', compact('entidad_type', 'entidad_id', 'entidad'));
    }

    /**
     * Guardar nuevo documento
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entidad_type' => 'required|in:App\Models\Contrato,App\Models\Servicio',
            'entidad_id' => 'required|integer',
            'archivo' => 'required|file|max:10240',
            'tipo_documento' => 'required|in:CONTRATO,SOPORTE,DIAGNOSTICO,FACTURA,OTRO',
            'descripcion' => 'nullable|string|max:500',
        ]);

        // Guardar archivo
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $path = $archivo->store('documentos', 'public');

            DocumentoAdjunto::create([
                'entidad_type' => $validated['entidad_type'],
                'entidad_id' => $validated['entidad_id'],
                'nombre_archivo' => $archivo->getClientOriginalName(),
                'ruta_archivo' => $path,
                'tipo_documento' => $validated['tipo_documento'],
                'mime_type' => $archivo->getMimeType(),
                'tamaño_bytes' => $archivo->getSize(),
                'descripcion' => $validated['descripcion'] ?? null,
                'subido_por' => auth()->id(),
            ]);
        }

        $returnUrl = $validated['entidad_type'] === 'App\Models\Contrato'
            ? route('contratos.show', $validated['entidad_id'])
            : route('servicios.show', $validated['entidad_id']);

        return redirect($returnUrl)
            ->with('success', 'Documento cargado exitosamente');
    }

    /**
     * Descargar documento
     */
    public function download(DocumentoAdjunto $documento): Response
    {
        $path = storage_path('app/public/' . $documento->ruta_archivo);

        if (!file_exists($path)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($path, $documento->nombre_archivo);
    }

    /**
     * Mostrar detalles de un documento
     */
    public function show(DocumentoAdjunto $documento): View
    {
        $documento->load('subidoPor');

        return view('documentos.show', compact('documento'));
    }

    /**
     * Eliminar documento
     */
    public function destroy(DocumentoAdjunto $documento): RedirectResponse
    {
        $entidad_type = $documento->entidad_type;
        $entidad_id = $documento->entidad_id;

        // Eliminar archivo físico
        if (\Storage::disk('public')->exists($documento->ruta_archivo)) {
            \Storage::disk('public')->delete($documento->ruta_archivo);
        }

        $documento->delete();

        $returnUrl = $entidad_type === 'App\Models\Contrato'
            ? route('contratos.show', $entidad_id)
            : route('servicios.show', $entidad_id);

        return redirect($returnUrl)
            ->with('success', 'Documento eliminado exitosamente');
    }
}
