<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria'; // ajusta al nombre real
    public $timestamps = false;

    // Ajusta columnas que vayas a insertar
    protected $fillable = [
        // 'caso_id','accion','usuario','ip','detalle','fecha'
    ];
}
