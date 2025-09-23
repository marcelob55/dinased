<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable;

    protected $fillable = [
        'nombres','apellidos','nickname','celular','cedula','correo','password',
        'agencia','equipo','rol',
    ];

    protected $hidden = ['password','remember_token'];

    // IMPORTANTE: si tu columna se llama "correo" en BD, mapea a email
    public function getEmailAttribute()
    {
        // Usa "correo" como email canónico para Notificaciones/Mails de Laravel
        return $this->attributes['correo'] ?? null;
    }

    // Para que las notificaciones de verificación usen la dirección correcta
    public function routeNotificationForMail($notification = null)
    {
        return $this->email; // viene del accessor anterior
    }
}