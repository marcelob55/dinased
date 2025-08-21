<?php
namespace App\Http\Controllers;

use App\Models\Caso;
use App\Models\DetalleCaso;
use Illuminate\Http\Request;

class DetalleCasoController extends Controller {
  public function edit(Caso $caso){
    $detalle = $caso->detalle;
    return view('casos.alimentar', compact('caso','detalle'));
  }

  public function store(Request $r, Caso $caso){
    $data = $r->validate([
      'verificacion'=>'nullable|string|max:255',
      'codigo_ecu'=>'nullable|string|max:50',
      'zona'=>'nullable|string|max:50',
      'subzona'=>'nullable|string|max:50',
      'distrito'=>'nullable|string|max:50',
      'circuito'=>'nullable|string|max:50',
      'subcircuito'=>'nullable|string|max:50',
      'espacio'=>'nullable|string|max:50',
      'area'=>'nullable|string|max:50',
      'lugar_hecho'=>'nullable|string|max:255',
      'fecha_hora'=>'nullable|string|max:50', // o datetime si tu columna lo es
      'coordenadas'=>'nullable|string|max:100',
      'criminalistica'=>'nullable|string|max:255',
      'tipo_arma'=>'nullable|string|max:100',
      'indicios'=>'nullable|string|max:10', // “Sí/No” por ahora
      'tipo_delito'=>'nullable|string|max:100',
      'motivacion'=>'nullable|string',
      'estado_caso'=>'nullable|string|max:50',
      'justificacion'=>'nullable|string',
      'circunstancias'=>'nullable|string',
      'entrevistas'=>'nullable|string',
      'actividades'=>'nullable|string',
      'reporta'=>'nullable|string|max:255',
    ]);

    $data['caso_id'] = $caso->id;

    $caso->detalle
      ? $caso->detalle->update($data)
      : DetalleCaso::create($data);

    return redirect()->route('casos.show',$caso)->with('ok','Detalle guardado');
  }
}
