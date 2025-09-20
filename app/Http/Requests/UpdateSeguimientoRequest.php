<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSeguimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normaliza algunos campos antes de validar (p.ej. checkbox).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'escena_misma' => filter_var($this->input('escena_misma'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules(): array
    {
        // Catálogos
        $fiscales       = (array) config('segjudicial.fiscales_delegados', []);
        $tiposPenales   = (array) config('segjudicial.tipos_penales', []);
        $medidas        = (array) config('segjudicial.medidas_cautelares', []);
        $detMedidas     = (array) config('segjudicial.detalles_medidas', []);
        $vinculacion    = (array) config('segjudicial.vinculacion', []);           // ej: ['SI','NO']
        $situaciones    = (array) config('segjudicial.situaciones_juridicas', []);
        $requerimientos = (array) config('segjudicial.requerimientos', []);
        $fiscNombres    = (array) config('segjudicial.fiscalias_nombres', []);
        $fiscNumeros    = (array) config('segjudicial.fiscalias_numeros', []);
        $escenas        = (array) config('segjudicial.escenas', []);

        return [
            // Nº de causa exacto
            'no_causa_no_fiscalia' => ['required','digits:15'],

            // Selects simples
            'nombres_del_fiscal_delegado' => ['nullable', Rule::in($fiscales)],
            'tipo_penal_en_audiencia_de_formulacion_de_cargos' => ['nullable', Rule::in($tiposPenales)],
            'tipo_de_medidas'       => ['nullable', Rule::in($medidas)],
            'detalle_de_medidas'    => ['nullable', Rule::in($detMedidas)],
            'existio_vinculacion_dentro_de_la_instruccion_fiscal' => ['required', Rule::in($vinculacion)],
            'situacion_juridica_actual' => ['nullable', Rule::in($situaciones)],

            // Fiscalía
            'fiscalia_nombre' => ['nullable', Rule::in($fiscNombres)],
            'fiscalia_numero' => ['nullable', Rule::in($fiscNumeros)],

            // Escenas
            'escena_levantamiento' => ['nullable', Rule::in($escenas)],
            'escena_suceso'        => ['nullable', Rule::in($escenas)],
            'escena_misma'         => ['sometimes','boolean'],

            // Multi-selects
            'requerimientos_realizados'   => ['nullable','array'],
            'requerimientos_realizados.*' => [Rule::in($requerimientos)],
            'requerimientos_pendientes'   => ['nullable','array'],
            'requerimientos_pendientes.*' => [Rule::in($requerimientos)],

            // Vinculados (multi-select de nombres libres)
            'vinculados'   => ['nullable','array'],
            'vinculados.*' => ['nullable','string','max:255'],

            // Texto libre
            'observacion' => ['nullable','string'],
        ];
    }

    public function messages(): array
    {
        return [
            'no_causa_no_fiscalia.required' => 'El No. de causa es obligatorio.',
            'no_causa_no_fiscalia.digits'   => 'El No. de causa debe tener 15 dígitos.',
            'existio_vinculacion_dentro_de_la_instruccion_fiscal.required' => 'Indica si hubo vinculación.',
            '*.in' => 'El valor seleccionado no es válido.',
        ];
    }
}
