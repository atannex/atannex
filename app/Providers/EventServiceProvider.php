<?php

namespace App\Providers;

use App\Events\Users\UserCreated;
use App\Listeners\SendContactMessage;
use App\Events\ContactMessageCreated;
use App\Events\Docs\DocumentCreated;
use App\Listeners\Docs\DocumentNotification;
use App\Listeners\Users\SendUserRegisteredNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        DocumentCreated::class => [
            DocumentNotification::class,
        ],

        UserCreated::class => [
            SendUserRegisteredNotification::class,
        ],

        ContactMessageCreated::class => [
            SendContactMessage::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void {}
}
