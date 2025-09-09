<?php

namespace App\Providers;

use App\Events\Users\UserCreated;
use App\Events\Docs\DocumentCreated;
use App\Listeners\SendDocs\DocumentNotification;
use App\Listeners\SendUsers\SendUsers\SendUserRegisteredNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // register event/listeners

        DocumentCreated::class => [
            DocumentNotification::class,
        ],
        UserCreated::class => [
            SendUserRegisteredNotification::class,
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
