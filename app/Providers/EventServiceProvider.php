<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use App\Listeners\LogUserLoginActivity;
use App\Listeners\LogUserLogoutActivity;
use Illuminate\Auth\Events\Authenticated;
use App\Listeners\UpdateUserLastSeen;

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
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
