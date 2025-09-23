<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
// Usa la interfaz genérica o tu modelo real:
use Illuminate\Contracts\Auth\Authenticatable;
// o si prefieres tipar a tu clase concreta:
// use App\Models\Usuario;
use Illuminate\Support\Carbon;

class LoginAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Authenticatable $user,   // <-- antes: App\Models\User
        public string $ip,
        public ?string $ua,
        public Carbon $when,
    ) {}

    public function build()
    {
        return $this->subject('Nuevo inicio de sesión')
            ->view('emails.login_alert');
    }
}
