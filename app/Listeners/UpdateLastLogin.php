<?php

// app/Listeners/UpdateLastLogin.php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLogin
{
    public function handle(Login $event): void
    {
        $event->user->forceFill([
            'ultima_conexion' => now()->format('Y-m-d H:i:s'),
            'ip_conexion'     => request()->ip(),
        ])->saveQuietly();
    }
}
