<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    // Tabla por convención es "casos"
    protected $fillable = [
        'numero_caso', // ¡obligatorio para evitar MassAssignment!
        'label',
        'fecha',
        'cedula',
        // agrega aquí si más columnas existen en "casos"
        // 'nombre_asociado', 'descripcion'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function detalle()
    {
        return $this->hasOne(DetalleCaso::class, 'caso_id');
    }

    public function victimas()
    {
        return $this->hasMany(Victima::class, 'caso_id');
    }
}
