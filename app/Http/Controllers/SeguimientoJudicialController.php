<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSeguimientoRequest;
use App\Models\Caso;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeguimientoJudicialController extends Controller
{
    /**
     * GET /casos/{caso}/seguimiento-judicial
     */
    public function create(Request $request, Caso $caso)
    {
        // Si ya existe seguimiento, lo editamos; si no, precreamos con caso_id
        $seguimiento = $caso->seguimiento ?: new Seguimiento(['caso_id' => $caso->id]);

        /* ---------------------------------------------------------
         * 1) Encabezado: detalle del caso + contexto
         * ---------------------------------------------------------*/
        $detalle = DB::table('detalle_caso')->where('caso_id', $caso->id)->first();

        // Coords (nombres alternativos)
        $lat = $detalle->lat ?? $detalle->latitud ?? null;
        $lng = $detalle->lng ?? $detalle->longitud ?? $detalle->lon ?? null;
		
		$toSiNo = function ($v) {
		if ($v === null) return null;
		$s = strtoupper(trim((string)$v));
		return $s === 'SI' ? 'SI' : ($s === 'NO' ? 'NO' : null);
};


        $contexto = [
			'verificacion'   => $detalle->verificacion ?? null,
			'ecu'            => $detalle->codigo_ecu ?? $detalle->codigo_ecu911 ?? null,
			'zona'           => $detalle->zona ?? null,
			'subzona'        => $detalle->subzona ?? null,
			'distrito'       => $detalle->distrito ?? null,
			'circuito'       => $detalle->circuito ?? null,
			'subcircuito'    => $detalle->subcircuito ?? null,
			'espacio'        => $detalle->espacio ?? null,
			'area'           => $detalle->area ?? null,

			'fecha_hora'     => $detalle->fecha_hora_del_hecho
								?? $detalle->fecha_hora_hecho
								?? $detalle->fecha_hecho
								?? null,

			'lugar_hecho'    => $detalle->lugar_del_hecho ?? $detalle->lugar_hecho ?? null,
			'coordenadas'    => ($lat && $lng) ? "{$lat}, {$lng}" : null,

			// AHORA respeta "SI"/"NO" guardados como texto
			'criminalistica' => $toSiNo($detalle->criminalistica ?? null),
			'indicios'       => $toSiNo($detalle->indicios ?? null),

			'tipo_arma'      => $detalle->tipo_de_arma ?? $detalle->tipo_arma ?? null,
			'tipo_delito'    => $detalle->tipo_de_delito ?? $detalle->tipo_delito ?? null,
			'estado_caso'    => $detalle->estado_del_caso ?? $detalle->estado_caso ?? null,
			'motivacion'     => $detalle->motivacion ?? null,
			'justificacion'  => $detalle->justificacion ?? null,

			// para mostrar lista debajo de "¿INDICIOS?"
			'indicios_lines' => !empty($detalle->indicios_detalle)
                        ? preg_split('/\r\n|\r|\n/', trim($detalle->indicios_detalle))
                        : [],
			];
		
		

        /* ---------------------------------------------------------
         * 2) Fallecidos / Heridos para el encabezado
         *    (flexible: intenta relaciones, si no, consulta BD)
         * ---------------------------------------------------------*/
        [$fallecidos, $heridos] = $this->extraerPersonasDelCaso($caso);
        $contexto['fallecidos'] = $fallecidos;
        $contexto['heridos']    = $heridos;

        /* ---------------------------------------------------------
         * 3) Catálogos de selects
         * ---------------------------------------------------------*/
        $cat = [
            'fiscales'          => config('segjudicial.fiscales_delegados', []),
            'tiposPenales'      => config('segjudicial.tipos_penales', []),
            'medidas'           => config('segjudicial.medidas_cautelares', []),
            'detalleMedidas'    => config('segjudicial.detalles_medidas', []),
            'vinculacion'       => config('segjudicial.vinculacion', []),
            'situaciones'       => config('segjudicial.situaciones_juridicas', []),
            'reqs'              => config('segjudicial.requerimientos', []),
            'fiscaliasNombres'  => config('segjudicial.fiscalias_nombres', []),
            'fiscaliasNumeros'  => config('segjudicial.fiscalias_numeros', []),
            'escenas'           => config('segjudicial.escenas', []),
        ];



/* ---------------------------------------------------------
 * 4) Nº de causa existentes (para sugerir)
 *    (fix MySQL 8: evitar DISTINCT + ORDER BY id)
 * ---------------------------------------------------------*/
$causas = DB::table('seguimiento')
    ->select('no_causa_no_fiscalia', DB::raw('MAX(id) AS last_id'))
    ->whereNotNull('no_causa_no_fiscalia')
    ->whereRaw("no_causa_no_fiscalia REGEXP '^[0-9]{15}$'")
    ->groupBy('no_causa_no_fiscalia')
    ->orderByDesc('last_id')
    ->limit(50)
    ->pluck('no_causa_no_fiscalia');





        /* ---------------------------------------------------------
         * 5) Vinculados sugeridos: víctimas + detenidos (nombres)
         * ---------------------------------------------------------*/
        $vinculados = $this->sugerirNombresVinculados($caso);

        return view('segjudicial.create', compact(
            'caso', 'seguimiento', 'contexto', 'cat', 'causas', 'vinculados'
        ));
    }

    /**
     * POST /casos/{caso}/seguimiento-judicial
     */
    public function store(UpdateSeguimientoRequest $request, Caso $caso)
    {
        $data = $request->validated();

        // Multi-selects → cadena separada por coma
        foreach (['requerimientos_realizados', 'requerimientos_pendientes'] as $k) {
            if (array_key_exists($k, $data)) {
                $data[$k] = is_array($data[$k])
                    ? implode(', ', array_filter($data[$k]))
                    : (string) $data[$k];
            }
        }

        // Vinculados (array) → una sola columna string
        if (isset($data['vinculados']) && is_array($data['vinculados'])) {
            $data['nombre_del_o_los_vinculados'] = implode(', ', array_filter($data['vinculados']));
            unset($data['vinculados']);
        }

        // Misma escena → si se marcó y no vino suceso, copia levantamiento
        if (!empty($data['escena_misma'])) {
            if (empty($data['escena_suceso']) && !empty($data['escena_levantamiento'])) {
                $data['escena_suceso'] = $data['escena_levantamiento'];
            }
            unset($data['escena_misma']);
        }

        // Guardar / actualizar
        $seguimiento = $caso->seguimiento;
        if ($seguimiento) {
            $seguimiento->update($data);
        } else {
            $seguimiento = $caso->seguimiento()->create($data);
        }

        return redirect()
            ->route('segjudicial.create', $caso)
            ->with('ok', 'Seguimiento judicial guardado.');
    }

    /* ======================================================================
     * Helpers privados
     * ======================================================================*/

    /**
     * Obtiene listas (Collection) de fallecidos y heridos para el encabezado.
     * Devuelve [Collection $fallecidos, Collection $heridos]
     *
     * Cada item es un objeto con ->nombre y ->cedula.
     */
	private function extraerPersonasDelCaso(Caso $caso): array
	{
		$mapPersona = function ($row) {
			$nombre = $this->armarNombre($row);
			return $nombre ? (object)[
				'nombre' => $nombre,
				'cedula' => $row->cedula ?? null,
			] : null;
		};

		$fallecidos = collect();
		$heridos    = collect();

		// 1) Si existe la relación victimas(), úsala y filtra por tipo
		if (method_exists($caso, 'victimas')) {
			try {
				$vics = $caso->victimas ?: collect();

				$fallecidos = $vics->where('tipo', 'occiso')
								   ->map($mapPersona)->filter()->values();

				$heridos    = $vics->where('tipo', 'herido')
								   ->map($mapPersona)->filter()->values();
			} catch (\Throwable $e) {
				// fallback BD abajo
			}
		}

		// 2) Fallback directo a la BD
		if ($fallecidos->isEmpty()) {
			try {
				$rows = \DB::table('victimas')
					->where('caso_id', $caso->id)->where('tipo', 'occiso')->get();
				$fallecidos = collect($rows)->map($mapPersona)->filter()->values();
			} catch (\Throwable $e) {}
		}

		if ($heridos->isEmpty()) {
			try {
				$rows = \DB::table('victimas')
					->where('caso_id', $caso->id)->where('tipo', 'herido')->get();
				$heridos = collect($rows)->map($mapPersona)->filter()->values();
			} catch (\Throwable $e) {}
		}

		return [$fallecidos, $heridos];
	}


    /**
     * Construye un nombre legible a partir de distintos esquemas de columnas.
     */
    private function armarNombre($row): ?string
    {
        // Combina 'nombres' + 'apellidos'
        $nombres   = trim(($row->nombres ?? '') . ' ' . ($row->apellidos ?? ''));
        if ($nombres !== '') return preg_replace('/\s+/', ' ', $nombres);

        // Campo único 'nombre'
        if (!empty($row->nombre)) return trim($row->nombre);

        // Campo tipo 'apellidos_nombres' o similar
        foreach (['apellidos_nombres', 'apellidos_y_nombres', 'nombre_completo'] as $c) {
            if (!empty($row->{$c})) return trim($row->{$c});
        }

        return null;
    }

    /**
     * Sugerencias de nombres para el multi-select "vinculados".
     */
    private function sugerirNombresVinculados(Caso $caso): Collection
    {
        $nombres = collect();

        // Relaciones si existen
        if (method_exists($caso, 'victimas')) {
            try {
                $nombres = $nombres->merge(
                    ($caso->victimas ?: collect())->map(fn($v) => $this->armarNombre($v))
                );
            } catch (\Throwable $e) {}
        }
        if (method_exists($caso, 'detenidos')) {
            try {
                $nombres = $nombres->merge(
                    ($caso->detenidos ?: collect())->map(fn($d) => $this->armarNombre($d))
                );
            } catch (\Throwable $e) {}
        }

        // Plan B: tablas directas
        if (Schema::hasTable('victimas')) {
            try {
                $rows = DB::table('victimas')->where('caso_id', $caso->id)->get();
                $nombres = $nombres->merge($rows->map(fn($r) => $this->armarNombre($r)));
            } catch (\Throwable $e) {}
        }
        if (Schema::hasTable('detenidos')) {
            try {
                $rows = DB::table('detenidos')->where('caso_id', $caso->id)->get();
                $nombres = $nombres->merge($rows->map(fn($r) => $this->armarNombre($r)));
            } catch (\Throwable $e) {}
        }

        return $nombres->filter()->unique()->values();
    }
}
