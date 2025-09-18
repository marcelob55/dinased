<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // 
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable   // ya no Model
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombres','apellidos','nickname','celular','cedula','contrasena',
        'correo','agencia','equipo','numero_caso','rol',
        'fecha_registro','ultima_conexion','ip_conexion',
    ];

    protected $hidden = ['contrasena'];

    // Laravel llamará a este método para validar el password
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}
