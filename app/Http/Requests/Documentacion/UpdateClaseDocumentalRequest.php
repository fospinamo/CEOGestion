<?php

namespace App\Http\Requests\Documentacion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClaseDocumentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descripcion' => 'required|string',
            'estado' => 'required|boolean',
            'version' => 'required|string|max:50',
            'observacion' => 'nullable|string',
            'realizado_por_id' => 'required|exists:users,id',
            'registrado_por_id' => 'required|exists:users,id',
            'revisado_por_id' => 'required|exists:users,id',
            'imagen' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
        ];
    }
}
