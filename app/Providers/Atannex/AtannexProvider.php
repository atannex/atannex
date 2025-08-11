<?php

namespace App\Providers\Atannex;

use App\Events\PostPublished;
use Ngangagah\Handlers\Navigation;
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
        // Event::listen(
        //     PostPublished::class,
        //     HandlePostPublished::class
        // );

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
