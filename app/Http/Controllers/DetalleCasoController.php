<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use App\Models\DetalleCaso;
use App\Models\Victima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleCasoController extends Controller
{
    public function edit(Caso $caso)
    {
        $detalle  = $caso->detalle;
        $victimas = $caso->victimas()->orderBy('tipo')->orderBy('etiqueta')->get();
        $fallecidos = $victimas->where('tipo', 'occiso')->values();
        $heridos    = $victimas->where('tipo', 'herido')->values();

        return view('casos.alimentar', compact('caso', 'detalle', 'fallecidos', 'heridos'));
    }

    public function store(Request $r, Caso $caso)
    {
        // ==== Validación base para detalle ====
        $r->validate([
            'verificacion'   => ['nullable','string','max:255'],
            'codigo_ecu'     => ['nullable','string','max:50'],
            'zona'           => ['nullable','string','max:50'],
            'subzona'        => ['nullable','string','max:50'],
            'distrito'       => ['nullable','string','max:50'],
            'circuito'       => ['nullable','string','max:50'],
            'subcircuito'    => ['nullable','string','max:50'],
            'espacio'        => ['nullable','string','max:50'],
            'area'           => ['nullable','string','max:50'],
            'lugar_hecho'    => ['nullable','string','max:255'],
            'coordenadas'    => ['nullable','string','max:100'],
            'criminalistica' => ['nullable','string'],
            'tipo_arma'      => ['nullable','string','max:100'],
            'indicios'       => ['nullable','string','max:50'],
            'tipo_delito'    => ['nullable','string','max:100'],
            'motivacion'     => ['nullable','string'],
            'estado_caso'    => ['nullable','string','max:50'],
            'justificacion'  => ['nullable','string'],
            'circunstancias' => ['nullable','string'],
            'fecha_hecho'    => ['nullable','date'],
            'hora_hecho'     => ['nullable'],

            'entrevistas'    => ['nullable','array'],
            'entrevistas.*'  => ['nullable','string'],
            'actividades'    => ['nullable','array'],
            'actividades.*'  => ['nullable','string'],

            // arreglos de víctimas
            'fallecidos'     => ['nullable','array'],
            'heridos'        => ['nullable','array'],
        ]);

        // Sanitiza arrays simples
        $entrevistas = array_values(array_filter($r->input('entrevistas', []), fn($v) => $v !== null && $v !== ''));
        $actividades = array_values(array_filter($r->input('actividades', []), fn($v) => $v !== null && $v !== ''));

        // Mapa de booleanos estilo "Sí/No"
        $mapBool = function ($v) {
            if ($v === null || $v === '') return null;
            $s = mb_strtolower(trim((string)$v));
            return in_array($s, ['si','sí','1','true','on','s','y']) ? 1 : 0;
        };

        $normTipo = fn($v, $fallback) => in_array(strtolower((string)$v), ['occiso','herido'], true)
            ? strtolower((string)$v) : $fallback;

        $normSexo = fn($v) => in_array(strtoupper((string)$v), ['M','F','I'], true)
            ? strtoupper((string)$v) : null;

        DB::transaction(function () use ($caso, $r, $entrevistas, $actividades, $mapBool, $normTipo, $normSexo) {

            // ===== Detalle: update-or-create por caso_id =====
            $detalleData = $r->only([
                'verificacion','codigo_ecu','zona','subzona','distrito','circuito','subcircuito',
                'espacio','area','lugar_hecho','coordenadas','criminalistica','tipo_arma','indicios',
                'tipo_delito','motivacion','estado_caso','justificacion','circunstancias',
                'fecha_hecho','hora_hecho','reporta'
            ]);
            $detalleData['entrevistas'] = $entrevistas;
            $detalleData['actividades'] = $actividades;

            DetalleCaso::updateOrCreate(
                ['caso_id' => $caso->id],
                $detalleData
            );

            // ===== Víctimas: upsert por (caso_id, tipo, etiqueta|cedula) =====
            $payload = [];

            foreach ((array)$r->input('fallecidos', []) as $row) {
                if (empty($row['nombres']) && empty($row['apellidos']) && empty($row['cedula'])) continue;
                $payload[] = [
                    'caso_id'     => $caso->id,
                    'tipo'        => $normTipo($row['tipo'] ?? 'occiso', 'occiso'),
                    'etiqueta'    => $row['etiqueta'] ?? ($row['cedula'] ?? null),
                    'nombres'     => $row['nombres'] ?? null,
                    'apellidos'   => $row['apellidos'] ?? null,
                    'cedula'      => $row['cedula'] ?? null,
                    'edad'        => $row['edad'] ?? null,
                    'sexo'        => $normSexo($row['sexo'] ?? null),
                    'alias'       => $row['alias'] ?? null,
                    'nacionalidad'=> $row['nacionalidad'] ?? null,
                    'profesion_ocupacion' => $row['profesion_ocupacion'] ?? null,
                    'movilizacion'=> $row['movilizacion'] ?? null,
                    'antecedentes'=> $mapBool($row['antecedentes'] ?? null),
                    'sajte_judicatura' => $mapBool($row['sajte_judicatura'] ?? null),
                    'noticia_del_delito_fiscalia' => $mapBool($row['noticia_del_delito_fiscalia'] ?? null),
                    'pertenece_gao' => $mapBool($row['pertenece_gao'] ?? null),
                    'gao_cargo_funcion' => $row['gao_cargo_funcion'] ?? null,
                ];
            }

            foreach ((array)$r->input('heridos', []) as $row) {
                if (empty($row['nombres']) && empty($row['apellidos']) && empty($row['cedula'])) continue;
                $payload[] = [
                    'caso_id'     => $caso->id,
                    'tipo'        => $normTipo($row['tipo'] ?? 'herido', 'herido'),
                    'etiqueta'    => $row['etiqueta'] ?? ($row['cedula'] ?? null),
                    'nombres'     => $row['nombres'] ?? null,
                    'apellidos'   => $row['apellidos'] ?? null,
                    'cedula'      => $row['cedula'] ?? null,
                    'edad'        => $row['edad'] ?? null,
                    'sexo'        => $normSexo($row['sexo'] ?? null),
                    'alias'       => $row['alias'] ?? null,
                    'nacionalidad'=> $row['nacionalidad'] ?? null,
                    'profesion_ocupacion' => $row['profesion_ocupacion'] ?? null,
                    'movilizacion'=> $row['movilizacion'] ?? null,
                    'antecedentes'=> $mapBool($row['antecedentes'] ?? null),
                    'sajte_judicatura' => $mapBool($row['sajte_judicatura'] ?? null),
                    'noticia_del_delito_fiscalia' => $mapBool($row['noticia_del_delito_fiscalia'] ?? null),
                    'pertenece_gao' => $mapBool($row['pertenece_gao'] ?? null),
                    'gao_cargo_funcion' => $row['gao_cargo_funcion'] ?? null,
                ];
            }

            // Upsert por llave lógica (caso_id, tipo, etiqueta)
            if (!empty($payload)) {
                DB::table('victimas')->upsert(
                    $payload,
                    ['caso_id','tipo','etiqueta'],
                    [
                        'nombres','apellidos','cedula','edad','sexo','alias','nacionalidad',
                        'profesion_ocupacion','movilizacion','antecedentes','sajte_judicatura',
                        'noticia_del_delito_fiscalia','pertenece_gao','gao_cargo_funcion'
                    ]
                );
            }
        });

        return redirect()->route('casos.show', $caso)->with('ok', 'Detalle guardado correctamente.');
    }
}
