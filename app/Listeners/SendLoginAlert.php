<?php

// app/Listeners/SendLoginAlert.php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\LoginAlertMail;
use Illuminate\Support\Facades\Mail;

class SendLoginAlert implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(Login $event): void
    {
        $user = $event->user;
        $ip   = request()->ip();
        $ua   = request()->header('User-Agent');

        Mail::to($user->email)->queue(new LoginAlertMail($user, $ip, $ua, now()));
    }
}
