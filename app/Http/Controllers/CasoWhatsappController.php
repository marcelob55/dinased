<?php

// app/Http/Controllers/CasoWhatsappController.php
namespace App\Http\Controllers;

use App\Models\Caso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CasoWhatsappController extends Controller
{
    public function show(Request $request, Caso $caso)
    {
        // detalle
        $detalle = DB::table('detalle_caso')->where('caso_id', $caso->id)->first();

        // víctimas (occisos/heridos) – usa tu misma tabla “victimas”
        $victimas = DB::table('victimas')->where('caso_id', $caso->id)->get();

        $fallecidos = $victimas->where('tipo', 'occiso')->values();
        $heridos    = $victimas->where('tipo', 'herido')->values();

        $mode = $request->query('mode', 'copy'); // copy | pdf
        $view = $mode === 'pdf' ? 'casos.whatsapp_pdf' : 'casos.whatsapp_copy';

        return view($view, [
            'caso'       => $caso,
            'detalle'    => $detalle,
            'fallecidos' => $fallecidos,
            'heridos'    => $heridos,
        ]);
    }
}
