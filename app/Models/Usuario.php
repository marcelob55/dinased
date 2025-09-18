<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';      // tabla existente
    protected $primaryKey = 'id';
    public $timestamps = false;         // tu tabla no usa created_at/updated_at

    protected $fillable = [
        'nombres','apellidos','nickname','celular','cedula','contrasena',
        'correo','agencia','equipo','caso','numero_caso','rol',
        'fecha_registro','ultima_conexion','ip_conexion',
    ];

    protected $hidden = ['contrasena'];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'ultima_conexion'=> 'datetime',
    ];
}
