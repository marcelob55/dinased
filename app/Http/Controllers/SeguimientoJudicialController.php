<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSeguimientoRequest;
use App\Models\Caso;
use App\Models\Seguimiento;
use Illuminate\Http\Request;

class SeguimientoJudicialController extends Controller
{
    // GET /casos/{caso}/seguimiento-judicial
    public function create(Request $request, Caso $caso)
    {
        // Si ya existe, lo editamos; si no, creamos nuevo
        $seguimiento = $caso->seguimiento ?: new Seguimiento(['caso_id' => $caso->id]);

        // Contexto útil desde el caso (ECU, verificación, etc. si los tienes)
        $contexto = [
            'ecu'          => $caso->codigo_ecu ?? null,
            'numero_caso'  => $caso->numero_caso ?? null,
            'fecha_hecho'  => $caso->fecha_hecho ?? null,
            'tipo_delito'  => $caso->tipo_delito ?? null,
            'motivacion'   => $caso->motivacion ?? null,
        ];

        // Catálogos de selects (lee de config/segjudicial.php)
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

        // Nº de causa existentes (opcional, por si quieres sugerir)
        $causas = Seguimiento::whereNotNull('no_causa_no_fiscalia')
            ->whereRaw("no_causa_no_fiscalia REGEXP '^[0-9]{15}$'")
            ->orderByDesc('id')
            ->distinct()
            ->limit(50)
            ->pluck('no_causa_no_fiscalia');

        // Vinculados sugeridos desde víctimas y detenidos
        $vinculados = collect([])
            ->merge(method_exists($caso, 'victimas')   ? ($caso->victimas?->pluck('nombre') ?? []) : [])
            ->merge(method_exists($caso, 'detenidos')  ? ($caso->detenidos?->pluck('nombre') ?? []) : [])
            ->filter()->unique()->values();

        return view('segjudicial.create', compact('caso','seguimiento','contexto','cat','causas','vinculados'));
    }

    // POST /casos/{caso}/seguimiento-judicial
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

    // Vinculados (array) → columna string
    if (isset($data['vinculados']) && is_array($data['vinculados'])) {
        $data['nombre_del_o_los_vinculados'] = implode(', ', array_filter($data['vinculados']));
        unset($data['vinculados']);
    }

    // Misma escena → copia valor si procede
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

}