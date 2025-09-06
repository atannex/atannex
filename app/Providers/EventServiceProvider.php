<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use App\Listeners\LogUserLoginActivity;
use App\Listeners\LogUserLogoutActivity;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserLoggedIn::class => [
            LogUserLoginActivity::class,
        ],
        UserLoggedOut::class => [
            LogUserLogoutActivity::class,
        ],
        \App\Events\UserWelcomeEmailSent::class => [
            \App\Listeners\LogWelcomeEmailSent::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
