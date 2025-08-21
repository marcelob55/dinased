<?php

<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable {
    protected $table = 'usuarios';
    public $timestamps = false;
    protected $fillable = ['nombres','apellidos','nickname','celular','cedula','contrasena','rol','correo','agencia','equipo'];
    protected $hidden = ['contrasena'];
}
