<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Victima extends Model
{
    protected $table = 'victimas';

protected $fillable = [
    'caso_id','tipo','etiqueta','nombres','apellidos','cedula','edad','sexo',
    'observacion',                 // <--
    'alias','nacionalidad','profesion_ocupacion','movilizacion',
    'antecedentes','antecedentes_det',
    'sajte_judicatura','sajte_judicatura_det',
    'noticia_del_delito_fiscalia','noticia_del_delito_fiscalia_det',
    'pertenece_gao','gao_cargo_funcion',
];

protected $casts = [
    'antecedentes' => 'boolean',
    'sajte_judicatura' => 'boolean',
    'noticia_del_delito_fiscalia' => 'boolean',
    'pertenece_gao' => 'boolean',
];

    public function caso()
    {
        return $this->belongsTo(Caso::class, 'caso_id');
    }
}

