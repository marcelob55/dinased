<?php

// app/Http/Controllers/CasoWhatsappController.php
namespace App\Http\Controllers;

use App\Models\Caso;
use App\Models\DetalleCaso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CasoWhatsappController extends Controller
{
    /**
     * Muestra el formato “Copiar WhatsApp” (o PDF si ?mode=pdf).
     */
    public function show(Request $request, Caso $caso)
    {
        // Traer detalle con Eloquent para aplicar casts (entrevistas/actividades como array)
        $detalle = DetalleCaso::find($caso->id);

        // --- Normalizadores para limpiar texto doble-escapado y viñetas ---
        // Des-escapa \r\n literales, \uXXXX doble-escapados y remueve invisibles (LRM/RLM, etc.)
        $unescape = function ($s) {
            if (!is_string($s)) return '';
            // Normaliza saltos literales
            $s = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $s);
            // Des-escapa secuencias \uHHHH (doble-escape)
            if (strpos($s, '\\u') !== false) {
                $s = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($m) {
                    return iconv('UCS-2BE', 'UTF-8', pack('n', hexdec($m[1])));
                }, $s);
            }
            // Quita invisibles (LRM/RLM, embeddings)
            // Reemplaza la línea de quitar viñetas por:
			$s = preg_replace('/^[\-\*\x{2022}\x{25CF}\x{25AA}\x{25A0}\x{25E6}\x{00B7}\x{2013}]\s*/u', '', $s);

            // Normaliza CRLF/CR a LF
            return trim(preg_replace("/\r\n?/", "\n", $s));
        };

        // Convierte string|array a array de líneas limpias, sin viñetas iniciales
        $toLines = function ($val) use ($unescape) {
            // Si viene como string JSON, intenta decodificar
            if (is_string($val)) {
                $tmp = json_decode($val, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $val = $tmp;
                }
            }
            $arr = is_array($val) ? $val : (strlen((string)$val) ? [$val] : []);
            $arr = array_map(function ($x) use ($unescape) {
                $x = $unescape((string)$x);
                // Quita viñetas comunes al inicio: -, •, * (bullet U+2022 en PCRE2 con \x{2022})
                return preg_replace('/^[-•\*\x{2022}]\s*/u', '', $x);
            }, $arr);
            return array_values(array_filter($arr, fn ($x) => $x !== ''));
        };

        if ($detalle) {
            // A estas alturas, con casts, entrevistas/actividades ya pueden venir como array.
            // Igual pasamos por filtros para sanear doble-escapes y caracteres invisibles.
            $detalle->entrevistas    = $toLines($detalle->entrevistas);
            $detalle->actividades    = $toLines($detalle->actividades);
            $detalle->circunstancias = $unescape((string) $detalle->circunstancias);
            $detalle->justificacion  = $unescape((string) $detalle->justificacion);
        }

        // Víctimas (occisos/heridos)
        $victimas   = DB::table('victimas')->where('caso_id', $caso->id)->get();
        $fallecidos = $victimas->where('tipo', 'occiso')->values();
        $heridos    = $victimas->where('tipo', 'herido')->values();

        // Vista: copy (por defecto) o pdf
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
