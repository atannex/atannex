<?php

namespace App\Providers\Atannex;

use App\Models\User;
use Atannex\GlobalData;
use App\Policies\UserPolicy;
use App\Policies\CommentPolicy;
use App\Models\Comments\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
     * It shares global data across all views.
     *
     * @param GlobalData $navigation The navigation handler instance.
     */
    public function boot(GlobalData $globalData): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::after(fn($user) => $user->hasRole('Super Admin') ? true : null);
        $this->shareGlobalData($globalData);
    }

    /**
     * Share navigation data with all views globally.
     *
     * This allows any Blade view to access the 'global' variable,
     * which contains structured navigation data (e.g., menus, links).
     *
     * @param GlobalData The navigation service providing GlobalData.
     */
    protected function shareGlobalData(GlobalData $globalData): void
    {
        View::share('global', $globalData->getGlobalData());
    }
}
