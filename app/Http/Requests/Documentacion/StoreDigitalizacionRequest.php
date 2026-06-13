<?php

namespace App\Http\Requests\Documentacion;

use Illuminate\Foundation\Http\FormRequest;

class StoreDigitalizacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_id' => 'required|exists:empresas,id',
            'sede_id' => 'required|exists:sedes,id',
            'proceso_id' => 'required|exists:procesos,id',
            'subproceso_id' => 'required|exists:subprocesos,id',
            'documento_id' => 'required|exists:documentos,id',
            'user_id' => 'nullable|exists:users,id',
            'radicacion_id' => 'nullable|exists:radicaciones,id',
            'titulo' => 'nullable|string|max:200',
            'fecha_documento' => 'nullable|date',
            'estado' => 'required|in:ACTIVO,INACTIVO',
            'archivo' => 'nullable|file|max:10240',
        ];
    }
}
