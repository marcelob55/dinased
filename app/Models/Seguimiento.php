<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $table = 'seguimiento'; // usa el nombre REAL de tu tabla
    protected $fillable = [
        'no_causa_no_fiscalia',
        'nombres_del_fiscal_delegado',
        'fiscalia_nombre','fiscalia_numero',
        'tipo_penal_en_audiencia_de_formulacion_de_cargos',
        'tipo_de_medidas','detalle_de_medidas',
        'existio_vinculacion_dentro_de_la_instruccion_fiscal',
        'nombre_del_o_los_vinculados',
        'situacion_juridica_actual',
        'requerimientos_realizados','requerimientos_pendientes',
        'observacion',
        'escena_levantamiento','escena_suceso',
    ];
    public $timestamps = false;

    public function caso()
	{ 
		return $this->belongsTo(Caso::class,'caso_id'); 
	}
}
