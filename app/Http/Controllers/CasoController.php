<?php
namespace App\Http\Controllers;

use App\Models\Caso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CasoController extends Controller {
  public function index(){
    $casos = Caso::latest('fecha')->paginate(15);
    return view('casos.index', compact('casos'));
  }

  public function create(){
    // Genera número/fecha por defecto como lo hacías antes
    $numero = 'Z4'.now()->format('Ymd').str_pad((Caso::whereDate('fecha',now()->toDateString())->count()+1),2,'0',STR_PAD_LEFT);
    $fecha = now()->toDateString();
    return view('casos.create', compact('numero','fecha'));
  }

  public function store(Request $r){
    $data = $r->validate([
      'numero_caso'=>'required|unique:casos,numero_caso',
      'label'=>'required|string|max:255',
      'fecha'=>'required|date',
      'cedula'=>'required|string'
    ]);
    $caso = Caso::create($data);
    return redirect()->route('detalle.edit',$caso)->with('ok','Caso creado; alimente el detalle.');
  }

  public function show(Caso $caso){
    $caso->load('detalle','usuario');
    return view('casos.show', compact('caso'));
  }
}
