<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeguimientoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Nº de causa exacto de 15 dígitos
            'no_causa_no_fiscalia' => ['nullable','regex:/^\d{15}$/'],

            // Selects "puros" (valores deben estar en los catálogos)
            'nombres_del_fiscal_delegado' => ['nullable','in:'.implode(',', config('segjudicial.fiscales_delegados'))],
            'tipo_penal_en_audiencia_de_formulacion_de_cargos' => ['nullable','in:'.implode(',', config('segjudicial.tipos_penales'))],

            'tipo_de_medidas'   => ['nullable','in:'.implode(',', config('segjudicial.medidas_cautelares'))],
            'detalle_de_medidas'=> ['nullable','in:'.implode(',', config('segjudicial.detalles_medidas'))],
            'existio_vinculacion_dentro_de_la_instruccion_fiscal' => ['nullable','in:'.implode(',', config('segjudicial.vinculacion'))],

            'situacion_juridica_actual' => ['nullable','in:'.implode(',', config('segjudicial.situaciones_juridicas'))],

            // Multi-selects; los transformamos a cadena en el controlador
            'requerimientos_realizados' => ['nullable','array'],
            'requerimientos_realizados.*' => ['in:'.implode(',', config('segjudicial.requerimientos'))],
            'requerimientos_pendientes' => ['nullable','array'],
            'requerimientos_pendientes.*' => ['in:'.implode(',', config('segjudicial.requerimientos'))],

            // Fiscalía
            'fiscalia_nombre' => ['nullable','in:'.implode(',', config('segjudicial.fiscalias_nombres'))],
            'fiscalia_numero' => ['nullable','in:'.implode(',', config('segjudicial.fiscalias_numeros'))],

            // Escenas
            'escena_levantamiento' => ['nullable','in:'.implode(',', config('segjudicial.escenas'))],
            'escena_suceso'        => ['nullable','in:'.implode(',', config('segjudicial.escenas'))],
            'escena_misma'         => ['sometimes','boolean'],

            // Vinculados (multi-select) → lo juntamos a string antes de guardar
            'vinculados' => ['nullable','array'],
            'vinculados.*' => ['string','max:255'],

            // Observación sigue siendo texto libre
            'observacion' => ['nullable','string'],
        ];
    }
}
