<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
	 
	 
	 
	 protected $listen = [
    \Illuminate\Auth\Events\Registered::class => [
        \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
        \App\Listeners\SendWelcomeMail::class,
    ],
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\SendLoginAlert::class,
        \App\Listeners\UpdateLastLogin::class,
    ],
];



    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
