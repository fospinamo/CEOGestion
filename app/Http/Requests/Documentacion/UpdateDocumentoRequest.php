<?php

namespace App\Http\Requests\Documentacion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $documentoId = $this->route('documento')?->id ?? $this->route('documento');

        return [
            'empresa_id' => 'required|exists:empresas,id',
            'sede_id' => 'required|exists:sedes,id',
            'proceso_id' => 'required|exists:procesos,id',
            'subproceso_id' => 'required|exists:subprocesos,id',
            'codigo' => 'required|string|max:50|unique:documentos,codigo,' . $documentoId,
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'version' => 'nullable|string|max:50',
            'estado' => 'required|in:VIGENTE,INACTIVO',
        ];
    }
}
