<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCaso extends Model
{
    protected $table = 'detalle_caso';

    // ¡IMPORTANTE! Esta tabla NO tiene created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'caso_id','verificacion','codigo_ecu','zona','subzona','distrito','circuito','subcircuito',
        'espacio','area','lugar_hecho','coordenadas','criminalistica','tipo_arma','indicios',
        'tipo_delito','motivacion','estado_caso','justificacion','circunstancias',
        'entrevistas','actividades','reporta','fecha_hecho','hora_hecho', 'fecha_hecho','hora_hecho',
        'fecha_levantamiento','hora_levantamiento','indicios_detalle',
    ];

    protected $casts = [
        'entrevistas' => 'array',
        'actividades' => 'array',
        'fecha_hecho' => 'date',
        // guardamos HH:MM:SS; el cast ayuda a normalizar cuando se lea
        'hora_hecho'  => 'datetime:H:i:s',
		'fecha_hecho'         => 'date',
        'fecha_levantamiento' => 'date',
    ];

    public function caso()
    {
        return $this->belongsTo(Caso::class, 'caso_id');
    }
}
