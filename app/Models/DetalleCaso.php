<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetalleCaso extends Model {
    protected $table = 'detalle_caso';
    public $timestamps = false;
    protected $fillable = [
      'caso_id','verificacion','codigo_ecu','zona','subzona','distrito','circuito','subcircuito',
      'espacio','area','lugar_hecho','fecha_hora','coordenadas','criminalistica','tipo_arma',
      'indicios','tipo_delito','motivacion','estado_caso','justificacion','circunstancias',
      'entrevistas','actividades','reporta'
    ];
    public function caso(){ return $this->belongsTo(Caso::class); }
}
