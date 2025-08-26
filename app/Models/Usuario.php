<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'nombres','apellidos','nickname','celular','cedula',
        'contrasena','correo','agencia','equipo','caso','rol'
    ];

    protected $hidden = ['contrasena'];

    // Laravel usará esta columna como password
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}
