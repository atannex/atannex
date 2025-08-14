<?php

namespace App\Providers\Atannex;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Events\PostPublished;
use App\Policies\CommentPolicy;
use App\Models\Comments\Comment;
use Ngangagah\Handlers\Navigation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use App\Listeners\HandlePostPublished;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for Atannex-specific bindings and shared data.
 */
class AtannexProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * This method is automatically called after all other services are registered.
     * It shares global navigation data across all views.
     *
     * @param Navigation $navigation The navigation handler instance.
     */
    public function boot(Navigation $navigation): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::after(fn($user) => $user->hasRole('Super Admin') ? true : null);

        Event::listen(
            PostPublished::class,
            HandlePostPublished::class
        );

        $this->shareNavigationData($navigation);
    }

    /**
     * Share navigation data with all views globally.
     *
     * This allows any Blade view to access the 'global' variable,
     * which contains structured navigation data (e.g., menus, links).
     *
     * @param Navigation $navigation The navigation service providing page navigation data.
     */
    protected function shareNavigationData(Navigation $navigation): void
    {
        View::share('global', $navigation->getPageNavigation());
    }
}
