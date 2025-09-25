<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SegJudicialDashboardController extends Controller
{
    public function index(Request $request)
    {
        $q     = trim((string)$request->input('q', ''));
        $zona  = trim((string)$request->input('zona', '')); // filtro por zona (opcional)

        /* =========================
         * KPI: total de CASOS
         * ========================= */
        $totalCasos = DB::table('casos')->count();

        /* =========================
         * Seguimientos CON N° de causa
         * ========================= */
        $segConCausa = Seguimiento::query()
            ->whereNotNull('no_causa_no_fiscalia')
            ->whereRaw("TRIM(no_causa_no_fiscalia) <> ''");
            // ->whereRaw("no_causa_no_fiscalia REGEXP '^[0-9]{15}$'"); // <- si quieres forzar 15 dígitos

        $totalSegConCausa = (clone $segConCausa)->count();

        /* =========================
         * Distribución por situación jurídica (puede respetar el filtro de zona, ver abajo)
         * ========================= */
        $situacionBase = DB::table('seguimiento as s')
            ->join('casos as c','c.id','=','s.caso_id')
            ->leftJoin('detalle_caso as d','d.caso_id','=','c.id')
            ->whereNotNull('s.no_causa_no_fiscalia')
            ->whereRaw("TRIM(s.no_causa_no_fiscalia) <> ''");

        if ($zona !== '') {
            // normalización de zona para el filtro
            $situacionBase->where(function($w) use ($zona){
                $w->whereRaw("TRIM(LEADING '0' FROM d.zona) = ?", [$zona])
                  ->orWhereRaw("UPPER(d.zona) = ?", ['ZONA '.$zona])
                  ->orWhere('d.zona', $zona)
                  ->orWhere('d.zona', str_pad($zona, 2, '0', STR_PAD_LEFT));
            });
        }

        $situaciones = (clone $situacionBase)
            ->selectRaw("COALESCE(NULLIF(TRIM(UPPER(s.situacion_juridica_actual)),''),'SIN DATO') AS situacion, COUNT(*) AS total")
            ->groupBy('situacion')
            ->orderByDesc('total')
            ->get();

        $chartLabels = $situaciones->pluck('situacion');
        $chartData   = $situaciones->pluck('total');

        /* =========================
         * Casos en ZONA 8 (KPI histórico que ya tenías)
         * ========================= */
        $totalZona8 = DB::table('casos as c')
            ->leftJoin('detalle_caso as d','d.caso_id','=','c.id')
            ->where(function($w){
                $w->whereRaw("TRIM(LEADING '0' FROM d.zona) = '8'")
                  ->orWhereRaw("UPPER(d.zona) = 'ZONA 8'")
                  ->orWhere('d.zona','08')
                  ->orWhere('d.zona','8');
            })
            ->count();

        /* =========================
         * Conteo de SEGUIMIENTOS con causa POR ZONA
         * (normaliza zona a '1'..'9', 'SIN ZONA' si no hay dato)
         * ========================= */
        $zonasCount = DB::table('seguimiento as s')
            ->join('casos as c','c.id','=','s.caso_id')
            ->leftJoin('detalle_caso as d','d.caso_id','=','c.id')
            ->whereNotNull('s.no_causa_no_fiscalia')
            ->whereRaw("TRIM(s.no_causa_no_fiscalia) <> ''")
            ->selectRaw("
                CASE
                    WHEN d.zona IS NULL OR TRIM(d.zona) = '' THEN 'SIN ZONA'
                    WHEN UPPER(d.zona) LIKE 'ZONA %'
                        THEN TRIM(REPLACE(UPPER(d.zona),'ZONA',''))
                    ELSE TRIM(LEADING '0' FROM d.zona)
                END AS zona_norm,
                COUNT(*) AS total
            ")
            ->groupBy('zona_norm')
            ->orderByRaw("
                CASE WHEN zona_norm REGEXP '^[0-9]+$' THEN 0 ELSE 1 END ASC,
                CAST(zona_norm AS UNSIGNED)
            ")
            ->get();

        /* =========================
         * Listado/paginación (SEG con causa) + búsqueda + filtro zona
         * ========================= */
        $rowsBase = DB::table('seguimiento as s')
            ->join('casos as c','s.caso_id','=','c.id')
            ->leftJoin('detalle_caso as d','d.caso_id','=','c.id')
            ->leftJoin('usuarios as u','u.cedula','=','c.cedula')
            ->whereNotNull('s.no_causa_no_fiscalia')
            ->whereRaw("TRIM(s.no_causa_no_fiscalia) <> ''");

        if ($zona !== '') {
            $rowsBase->where(function($w) use ($zona){
                $w->whereRaw("TRIM(LEADING '0' FROM d.zona) = ?", [$zona])
                  ->orWhereRaw("UPPER(d.zona) = ?", ['ZONA '.$zona])
                  ->orWhere('d.zona', $zona)
                  ->orWhere('d.zona', str_pad($zona, 2, '0', STR_PAD_LEFT));
            });
        }

        if ($q !== '') {
            $like = '%'.str_replace(' ', '%', $q).'%';
            $rowsBase->where(function($s) use ($like){
                $s->where('c.numero_caso','like',$like)
                  ->orWhere('c.cedula','like',$like)
                  ->orWhere('u.cedula','like',$like)
                  ->orWhere('u.nombres','like',$like)
                  ->orWhere('u.apellidos','like',$like)
                  ->orWhere('s.nombre_del_o_los_vinculados','like',$like)
                  ->orWhere('s.nombres_del_fiscal_delegado','like',$like)
                  ->orWhere('s.no_causa_no_fiscalia','like',$like);
            });
        }

        $rows = $rowsBase
            ->select([
                's.id as seg_id',
                'c.id',
                'c.fecha',
                'c.numero_caso',
                DB::raw("COALESCE(u.nombres,'') AS agente_nombres"),
                DB::raw("COALESCE(u.apellidos,'') AS agente_apellidos"),
                'u.cedula as agente_cedula',
                'd.distrito',
                'd.zona',
                's.no_causa_no_fiscalia',
                's.situacion_juridica_actual',
                's.nombre_del_o_los_vinculados',
                's.nombres_del_fiscal_delegado',
            ])
            ->orderByDesc('c.fecha')
            ->orderByDesc('s.id')
            ->paginate(50)
            ->withQueryString();

        return view('segjudicial.dashboard', compact(
            'q',
            'zona',
            'totalCasos',
            'totalSegConCausa',
            'totalZona8',
            'zonasCount',
            'rows',
            'chartLabels',
            'chartData',
            'situaciones'
        ));
    }
}
