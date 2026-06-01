<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Equipo;
use App\Models\EquipoDocumento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EquipoDocumentoController extends Controller
{
    /**
     * Ver documentos de un equipo
     */
    public function index($equipoId)
    {
        $equipo = Equipo::with('documentos')->findOrFail($equipoId);
        $documentos = $equipo->documentos()->orderBy('tipo')->orderBy('created_at', 'desc')->get();
        
        return view('parametros.equipos.documentos.index', compact('equipo', 'documentos'));
    }

    /**
     * Formulario de upload
     */
    public function create($equipoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $tipos = [
            'visual' => 'Visual del Equipo',
            'hojas_vida' => 'Hojas de Vida',
            'reportes_anexos' => 'Reportes Anexos',
            'facturas' => 'Facturas',
            'certificados' => 'Certificados',
            'actas' => 'Actas',
        ];
        
        return view('parametros.equipos.documentos.create', compact('equipo', 'tipos'));
    }

    /**
     * Guardar documento
     */
    public function store(Request $request, $equipoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        
        $request->validate([
            'tipo' => 'required|in:visual,hojas_vida,reportes_anexos,facturas,certificados,actas',
            'archivo' => 'required|file|max:102400', // 100MB
            'descripcion' => 'nullable|string|max:500',
        ], [
            'archivo.max' => 'El archivo no debe exceder 100MB'
        ]);

        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $mimeType = $archivo->getMimeType();
        $tamaño = $archivo->getSize();

        // Guardar archivo
        $rutaGuardada = $archivo->store("equipos/{$equipo->id}/documentos", 'private');

        // Crear registro
        EquipoDocumento::create([
            'equipo_id' => $equipo->id,
            'tipo' => $request->tipo,
            'nombre_original' => $nombreOriginal,
            'archivo_path' => $rutaGuardada,
            'mime_type' => $mimeType,
            'tamaño_bytes' => $tamaño,
            'usuario_id' => auth()->id(),
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('parametros.equipos.documentos.index', $equipo->id)
                        ->with('success', 'Documento cargado exitosamente');
    }

    /**
     * Descargar documento
     */
    public function download($equipoId, $documentoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $documento = EquipoDocumento::where('equipo_id', $equipo->id)
                                     ->findOrFail($documentoId);

        return Storage::disk('private')->download($documento->archivo_path, $documento->nombre_original);
    }

    /**
     * Eliminar documento
     */
    public function destroy($equipoId, $documentoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $documento = EquipoDocumento::where('equipo_id', $equipo->id)
                                     ->findOrFail($documentoId);

        // Eliminar archivo físico
        if (Storage::disk('private')->exists($documento->archivo_path)) {
            Storage::disk('private')->delete($documento->archivo_path);
        }

        $documento->delete();

        return redirect()->route('parametros.equipos.documentos.index', $equipo->id)
                        ->with('success', 'Documento eliminado');
    }
}
