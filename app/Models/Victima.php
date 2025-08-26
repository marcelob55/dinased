<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Victima extends Model
{
    protected $table = 'victimas';

    protected $fillable = [
        'caso_id','tipo','etiqueta','nombres','apellidos','cedula','edad','sexo','alias',
        'nacionalidad','profesion_ocupacion','movilizacion','antecedentes','sajte_judicatura',
        'noticia_del_delito_fiscalia','pertenece_gao','gao_cargo_funcion',
    ];

    protected $casts = [
        'antecedentes'               => 'boolean',
        'sajte_judicatura'           => 'boolean',
        'noticia_del_delito_fiscalia'=> 'boolean',
        'pertenece_gao'              => 'boolean',
    ];

    public function caso()
    {
        return $this->belongsTo(Caso::class, 'caso_id');
    }
}

