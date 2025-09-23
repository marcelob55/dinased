<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use App\Models\DetalleCaso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleCasoController extends Controller
{


public function edit(Caso $caso)
{
    $detalle  = $caso->detalle;

    // Trae todas las víctimas y normaliza los booleanos a "Sí"/"No"
    $victimas = $caso->victimas()
        ->orderBy('tipo')->orderBy('etiqueta')
        ->get()
        ->map(function ($v) {
            $yesNo = function ($val) {
                return $val === null ? '' : ($val ? 'Sí' : 'No');
            };

            return [
                'tipo'          => $v->tipo,
                'etiqueta'      => $v->etiqueta,
                'nombres'       => $v->nombres,
                'apellidos'     => $v->apellidos,
                'cedula'        => $v->cedula,
                'edad'          => $v->edad,
                'sexo'          => $v->sexo,
                'observacion'   => $v->observacion,

                'alias'         => $v->alias,
                'nacionalidad'  => $v->nacionalidad,
                'ocupacion'     => $v->profesion_ocupacion,
                'movilizacion'  => $v->movilizacion,

                // ← aquí la normalización clave
                'antecedentes'            => $yesNo($v->antecedentes),
                'antecedentes_det'        => $v->antecedentes_det,

                'sajte'                   => $yesNo($v->sajte_judicatura),
                'sajte_det'               => $v->sajte_judicatura_det,

                'noticia_fiscalia'        => $yesNo($v->noticia_del_delito_fiscalia),
                'noticia_fiscalia_det'    => $v->noticia_del_delito_fiscalia_det,

                'gao'                     => $yesNo($v->pertenece_gao),
                'gao_det'                 => $v->gao_cargo_funcion,
            ];
        });

    $fallecidos = $victimas->where('tipo', 'occiso')->values();
    $heridos    = $victimas->where('tipo', 'herido')->values();

    return view('casos.alimentar', compact('caso', 'detalle', 'fallecidos', 'heridos'));
}




    public function store(Request $r, Caso $caso)
    {
        // ==== Validación base para detalle ====
        $r->validate([
            'verificacion'        => ['nullable','string','max:255'],
            'codigo_ecu'          => ['nullable','string','max:50'],
            'zona'                => ['nullable','string','max:50'],
            'subzona'             => ['nullable','string','max:50'],
            'distrito'            => ['nullable','string','max:50'],
            'circuito'            => ['nullable','string','max:50'],
            'subcircuito'         => ['nullable','string','max:50'],
            'espacio'             => ['nullable','string','max:50'],
            'area'                => ['nullable','string','max:50'],
            'lugar_hecho'         => ['nullable','string','max:255'],
            'coordenadas'         => ['nullable','string','max:100'],
            'criminalistica'      => ['nullable','string'],
            'tipo_arma'           => ['nullable','string','max:100'],
            'tipo_arma_otro'      => ['nullable','string','max:100'],
            'indicios'            => ['nullable','string','max:50'],
            'indicios_detalle'    => ['nullable','string'],
            'tipo_delito'         => ['nullable','string','max:100'],
            'tipo_delito_otro'    => ['nullable','string','max:100'],
            'motivacion'          => ['nullable','string'],
            'motivacion_otro'     => ['nullable','string'],
            'estado_caso'         => ['nullable','string','max:50'],
            'estado_caso_otro'    => ['nullable','string','max:100'],
            'reporta'             => ['nullable','string','max:100'],
            'reporta_otro'        => ['nullable','string','max:100'],
            'justificacion'       => ['nullable','string'],
            'circunstancias'      => ['nullable','string'],
            'fecha_hecho'         => ['nullable','date'],
            'hora_hecho'          => ['nullable'],
            'fecha_levantamiento' => ['nullable','date'],
            'hora_levantamiento'  => ['nullable'],
            'entrevistas'         => ['nullable','array'],
            'entrevistas.*'       => ['nullable','string'],
            'actividades'         => ['nullable','array'],
            'actividades.*'       => ['nullable','string'],

            // arreglos de víctimas
            'fallecidos'          => ['nullable','array'],
            'heridos'             => ['nullable','array'],
        ]);

        // Sanitiza arrays simples (los textareas llegan como array con 1 ítem)
        $entrevistas = array_values(array_filter(
            $r->input('entrevistas', []),
            fn($v) => $v !== null && $v !== ''
        ));
        $actividades = array_values(array_filter(
            $r->input('actividades', []),
            fn($v) => $v !== null && $v !== ''
        ));

        // Mapa de booleanos estilo "Sí/No"
        $mapBool = function ($v) {
            if ($v === null || $v === '') return null;
            $s = mb_strtolower(trim((string)$v));
            return in_array($s, ['si','sí','sí','1','true','on','s','y']) ? 1 : 0;
        };

        $normTipo = fn($v, $fallback) => in_array(strtolower((string)$v), ['occiso','herido'], true)
            ? strtolower((string)$v) : $fallback;

        $normSexo = fn($v) => in_array(strtoupper((string)$v), ['M','F','I'], true)
            ? strtoupper((string)$v) : null;

        DB::transaction(function () use ($caso, $r, $entrevistas, $actividades, $mapBool, $normTipo, $normSexo) {

            // ===== Detalle: update-or-create por caso_id =====
            // Normaliza campos con “OTRO”
            $tipoArma   = $r->input('tipo_arma')   === 'OTRO' ? $r->input('tipo_arma_otro')   : $r->input('tipo_arma');
            $tipoDelito = $r->input('tipo_delito') === 'OTRO' ? $r->input('tipo_delito_otro') : $r->input('tipo_delito');
            $estadoCaso = $r->input('estado_caso') === 'OTRO' ? $r->input('estado_caso_otro') : $r->input('estado_caso');
            $motivacion = $r->input('motivacion')  === 'OTRO' ? $r->input('motivacion_otro')  : $r->input('motivacion');
            $reporta    = $r->input('reporta')     === 'OTRO' ? $r->input('reporta_otro')     : $r->input('reporta');

            $detalleData = [
                'verificacion'        => $r->input('verificacion'),
                'codigo_ecu'          => $r->input('codigo_ecu'),
                'zona'                => $r->input('zona'),
                'subzona'             => $r->input('subzona'),
                'distrito'            => $r->input('distrito'),
                'circuito'            => $r->input('circuito'),
                'subcircuito'         => $r->input('subcircuito'),
                'espacio'             => $r->input('espacio'),
                'area'                => $r->input('area'),
                'lugar_hecho'         => $r->input('lugar_hecho'),
                'coordenadas'         => $r->input('coordenadas'),
                'criminalistica'      => $r->input('criminalistica'),
                'tipo_arma'           => $tipoArma,
                'indicios'            => $r->input('indicios'),
                'indicios_detalle'    => $r->input('indicios_detalle'),
                'tipo_delito'         => $tipoDelito,
                'motivacion'          => $motivacion,
                'estado_caso'         => $estadoCaso,
                'reporta'             => $reporta,
                'justificacion'       => $r->input('justificacion'),
                'circunstancias'      => $r->input('circunstancias'),
                'fecha_hecho'         => $r->input('fecha_hecho'),
                'hora_hecho'          => $r->input('hora_hecho'),
                'fecha_levantamiento' => $r->input('fecha_levantamiento'),
                'hora_levantamiento'  => $r->input('hora_levantamiento'),
                'entrevistas'         => $entrevistas,
                'actividades'         => $actividades,
            ];

            DetalleCaso::updateOrCreate(['caso_id' => $caso->id], $detalleData);

            // ===== Víctimas: upsert por (caso_id, tipo, etiqueta) =====
            $fromRows = function (array $rows, string $tipoPorDefecto) use ($caso, $normTipo, $normSexo, $mapBool) {
                $out = [];
                foreach ($rows as $row) {
                    if (empty($row['nombres']) && empty($row['apellidos']) && empty($row['cedula'])) {
                        continue;
                    }

                    $out[] = [
                        'caso_id'                         => $caso->id,
                        'tipo'                            => $normTipo($row['tipo'] ?? $tipoPorDefecto, $tipoPorDefecto),
                        'etiqueta'                        => $row['etiqueta'] ?? ($row['cedula'] ?? null),

                        'nombres'                         => $row['nombres'] ?? null,
                        'apellidos'                       => $row['apellidos'] ?? null,
                        'cedula'                          => $row['cedula'] ?? null,
                        'edad'                            => $row['edad'] ?? null,
                        'sexo'                            => $normSexo($row['sexo'] ?? null),
                        'observacion'                     => $row['observacion'] ?? null,

                        'alias'                           => $row['alias'] ?? null,
                        'nacionalidad'                    => $row['nacionalidad'] ?? null,
                        'profesion_ocupacion'             => $row['ocupacion'] ?? null, // la vista usa "ocupacion"
                        'movilizacion'                    => $row['movilizacion'] ?? null,

                        'antecedentes'                    => $mapBool($row['antecedentes'] ?? null),
                        'antecedentes_det'                => $row['antecedentes_det'] ?? null,

                        'sajte_judicatura'                => $mapBool($row['sajte'] ?? null),
                        'sajte_judicatura_det'            => $row['sajte_det'] ?? null,

                        'noticia_del_delito_fiscalia'     => $mapBool($row['noticia_fiscalia'] ?? null),
                        'noticia_del_delito_fiscalia_det' => $row['noticia_fiscalia_det'] ?? null,

                        'pertenece_gao'                   => $mapBool($row['gao'] ?? null),
                        'gao_cargo_funcion'               => $row['gao_det'] ?? null,
                    ];
                }
                return $out;
            };

            $payload = array_merge(
                $fromRows((array)$r->input('fallecidos', []), 'occiso'),
                $fromRows((array)$r->input('heridos', []),   'herido')
            );

            if (!empty($payload)) {
                DB::table('victimas')->upsert(
                    $payload,
                    ['caso_id', 'tipo', 'etiqueta'],
                    [
                        'nombres','apellidos','cedula','edad','sexo','observacion',
                        'alias','nacionalidad','profesion_ocupacion','movilizacion',
                        'antecedentes','antecedentes_det',
                        'sajte_judicatura','sajte_judicatura_det',
                        'noticia_del_delito_fiscalia','noticia_del_delito_fiscalia_det',
                        'pertenece_gao','gao_cargo_funcion',
                    ]
                );
            }
        });

       
	   
	   // App/Http/Controllers/DetalleCasoController.php
return redirect()
    ->route('detalle.edit', $caso)
    ->with('ok', 'Detalle guardado correctamente.');

	   
    }
}
