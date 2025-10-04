<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCaso extends Model
{
    protected $table = 'detalle_caso';
    protected $primaryKey = 'caso_id';
    public $incrementing = false;     // PK no autoincrementa
    protected $keyType = 'int';
    public $timestamps = false;       // no created_at/updated_at

    protected $fillable = [
        'caso_id','verificacion','codigo_ecu','zona','subzona','distrito','circuito','subcircuito',
        'espacio','area','lugar_hecho','coordenadas','criminalistica','tipo_arma','indicios',
        'indicios_detalle','tipo_delito','motivacion','estado_caso','justificacion','circunstancias',
        'entrevistas','actividades','reporta','fecha_hecho','fecha_levantamiento','hora_hecho','hora_levantamiento'
    ];

    protected $casts = [
    'entrevistas' => 'array',
    'actividades' => 'array',
	'fecha_hecho'=>'date','fecha_levantamiento'=>'date',
    'hora_hecho'=>'datetime:H:i:s','hora_levantamiento'=>'datetime:H:i:s'
    ];
	

    public function caso(){ return $this->belongsTo(Caso::class,'caso_id'); }
}
