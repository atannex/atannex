<?php

namespace App\Providers;

use App\Events\Users\UserCreated;
use App\Events\Docs\DocumentCreated;
use App\Events\Posts\Posts\BreakingPost;
use App\Listeners\SendDocs\DocumentNotification;
use App\Listeners\SendPosts\BreakingPostListener;
use App\Listeners\SendUsers\SendUserRegisteredNotification;
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

        BreakingPost::class => [
            BreakingPostListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
