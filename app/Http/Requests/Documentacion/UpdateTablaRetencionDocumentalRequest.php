<?php

namespace App\Http\Requests\Documentacion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTablaRetencionDocumentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_id' => 'required|exists:empresas,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_creacion' => 'required|date',
            'fecha_aprobacion' => 'nullable|date',
            'aprobado_comite' => 'nullable|boolean',
            'convalidado_agn' => 'nullable|boolean',
            'estado' => 'required|boolean',
            'observacion' => 'nullable|string',
            'usuario_crea_id' => 'required|exists:users,id',

            'detalles' => 'required|array|min:1',
            'detalles.*.dependencia' => 'required|string|max:255',
            'detalles.*.serie' => 'required|string|max:255',
            'detalles.*.subserie' => 'nullable|string|max:255',
            'detalles.*.tipo_documental' => 'required|string|max:255',
            'detalles.*.archivo_gestion_tiempo' => 'required|string|max:100',
            'detalles.*.archivo_central_tiempo' => 'required|string|max:100',
            'detalles.*.disposicion_final' => 'required|in:CP,EL,D',
            'detalles.*.observaciones' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'detalles.required' => 'Debe agregar al menos un registro a la TRD.',
            'detalles.min' => 'Debe agregar al menos un registro a la TRD.',
            'detalles.*.dependencia.required' => 'La dependencia es obligatoria.',
            'detalles.*.serie.required' => 'La serie es obligatoria.',
            'detalles.*.tipo_documental.required' => 'El tipo documental es obligatorio.',
            'detalles.*.archivo_gestion_tiempo.required' => 'El tiempo de Archivo de Gestión es obligatorio.',
            'detalles.*.archivo_central_tiempo.required' => 'El tiempo de Archivo Central es obligatorio.',
            'detalles.*.disposicion_final.required' => 'La disposición final es obligatoria.',
            'detalles.*.disposicion_final.in' => 'La disposición final debe ser CP, EL o D.',
        ];
    }
}
