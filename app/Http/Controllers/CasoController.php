<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CasoController extends Controller
{
    /** Listado */
    public function index()
    {
        // Ordena por fecha (desc) y pagina de 25 en 25
        $casos = Caso::latest('fecha')->paginate(25);
        return view('casos.index', compact('casos'));
    }

    /** Form crear */
    public function create()
    {
        // Si quieres mostrar el número ya generado en el form, descomenta:
        $zona   = Auth::user()->zona ?? '04';
        $numero = $this->generarNumeroCaso($zona);

        $fecha  = now()->toDateString();
        return view('casos.create', compact('numero', 'fecha'));
    }

    /** Guardar nuevo caso */
    public function store(Request $r)
    {
        $r->validate([
            'label'  => ['required','string','max:255'],
            'fecha'  => ['required','date'],
            'cedula' => ['required','string','max:20'],
        ]);

        $zona   = Auth::user()->zona ?? '04';
        $numero = $this->generarNumeroCaso($zona);

        $caso = Caso::create([
            'numero_caso' => $numero,
            'label'       => $r->label,
            'fecha'       => $r->fecha,
            'cedula'      => $r->cedula,
        ]);

        return redirect()->route('detalle.edit', $caso)->with('ok', 'Caso creado.');
    }

    /** Ver detalle del caso */
    public function show(Caso $caso)
    {
        // Carga el detalle y ordena víctimas: primero por tipo, luego por etiqueta y finalmente por id
        $caso->load([
            'detalle',
            'victimas' => function ($q) {
                $q->orderBy('tipo')->orderBy('etiqueta')->orderBy('id');
            },
        ]);

        // Separa colecciones para la vista
        $fallecidos = $caso->victimas->where('tipo', 'occiso')->values();
        $heridos    = $caso->victimas->where('tipo', 'herido')->values();

        return view('casos.show', compact('caso', 'fallecidos', 'heridos'));
    }

    /**
     * Genera número: Z{zona2}I{ddmmaaaa}{secuencia4}
     * p.ej. Z04I26082025 0001
     */
    private function generarNumeroCaso(string $zona): string
    {
        // Normaliza la zona a 2 dígitos
        $zona2 = str_pad(preg_replace('/\D/', '', $zona), 2, '0', STR_PAD_LEFT);
        $hoy   = now()->format('dmY');
        $pref  = "Z{$zona2}I{$hoy}";

        $ultimo = Caso::where('numero_caso', 'like', $pref.'%')
            ->orderBy('numero_caso', 'desc')
            ->value('numero_caso');

        $seq = $ultimo ? ((int)substr($ultimo, -4) + 1) : 1;

        return $pref . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /** Exportar PDF (narrativa) */
    public function exportarPDF(Caso $caso)
    {
        $caso->load(['detalle','victimas']);

        $pdf = Pdf::loadView('casos.pdf_narrativa', [
            'caso'       => $caso,
            'fallecidos' => $caso->victimas->where('tipo','occiso'),
            'heridos'    => $caso->victimas->where('tipo','herido'),
        ]);

        // stream() abre en el navegador; download() descarga
        return $pdf->stream('Caso_'.$caso->numero_caso.'.pdf');
    }
}
