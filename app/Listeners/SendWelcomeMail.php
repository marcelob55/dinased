<?php


// app/Listeners/SendWelcomeMail.php
namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeMail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(Registered $event): void
    {
        $user = $event->user;
        // envía correo de bienvenida al email del usuario
        Mail::to($user->email)->send(new WelcomeMail($user));
    }
}
