<?php

namespace App\Http\Requests\Documentacion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRadicacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $radicacionId = $this->route('radicacion')?->id ?? $this->route('radicacion');

        return [
            'empresa_id' => 'required|exists:empresas,id',
            'sede_id' => 'required|exists:sedes,id',
            'documento_id' => 'required|exists:documentos,id',
            'numero' => 'required|string|max:50|unique:radicaciones,numero,' . $radicacionId,
            'fecha_radicacion' => 'required|date',
            'tipo' => 'required|in:ENTRADA,SALIDA,INTERNA',
            'remitente' => 'nullable|string|max:200',
            'asunto' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:ABIERTA,CERRADA',
        ];
    }
}
