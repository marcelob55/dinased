<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;



class CasoController extends Controller
{
    public function index()
    {
        $casos = Caso::latest('fecha')->paginate(15);
        return view('casos.index', compact('casos'));
    }

    public function create()
    {
        $zona   = Auth::user()->zona ?? '04';     // TODO: usa zona real cuando la tengas
        $numero = $this->generarNumeroCaso($zona);
        $fecha  = now()->toDateString();

        return view('casos.create', compact('numero', 'fecha'));
    }

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




public function show(Caso $caso)
{
    $caso->load([
        'detalle',
        'victimas' => fn($q) => $q->orderBy('tipo')->orderBy('etiqueta')->orderBy('id'),
    ]);

    $fallecidos = $caso->victimas->where('tipo', 'occiso')->values();
    $heridos    = $caso->victimas->where('tipo', 'herido')->values();

    return view('casos.show', compact('caso', 'fallecidos', 'heridos'));
}






    /**
     * Z{zona}I{ddmmaaaa}{secuencia4}
     */
    private function generarNumeroCaso(string $zona): string
    {
        $zona2 = str_pad(preg_replace('/\D/', '', $zona), 2, '0', STR_PAD_LEFT);
        $hoy   = now()->format('dmY');
        $pref  = "Z{$zona2}I{$hoy}";

        $ultimo = Caso::where('numero_caso', 'like', $pref.'%')
            ->orderBy('numero_caso', 'desc')
            ->value('numero_caso');

        $seq = $ultimo ? ((int)substr($ultimo, -4) + 1) : 1;
        return $pref . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
	
	

public function exportarPDF(Caso $caso)
{
    $caso->load(['detalle','victimas']);
    $pdf = Pdf::loadView('casos.pdf_narrativa', [
        'caso' => $caso,
        'fallecidos' => $caso->victimas->where('tipo','occiso'),
        'heridos'    => $caso->victimas->where('tipo','herido'),
    ]);
    return $pdf->stream('Caso_'.$caso->numero_caso.'.pdf'); // o ->download(...)
}


	
	
}

