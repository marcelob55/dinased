<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
// use Illuminate\Auth\Listeners\SendEmailVerificationNotification; // <- opcional si usas verificación
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
// ⬇️ comenta la línea de tu listener de login
// use App\Listeners\SendLoginAlert;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            // SendEmailVerificationNotification::class, // si no usas verificación por email, comenta también
        ],

        // ⬇️ QUITA/COMENTA ESTE BLOQUE SI EXISTE
        // \Illuminate\Auth\Events\Login::class => [
        //     SendLoginAlert::class,
        // ],
    ];

    public function boot() {}
    public function shouldDiscoverEvents() { return false; }
}
