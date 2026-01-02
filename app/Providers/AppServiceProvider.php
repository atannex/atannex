<?php

namespace App\Providers;

use App\Models\Regions\Widget;
use App\Models\Regions\Section;
use App\Observers\WidgetObserver;
use App\Observers\SectionObserver;
use Atannex\Adapters\WidgetAdapter;
use Atannex\Adapters\SectionAdapter;
use Illuminate\Support\ServiceProvider;


/**
 * Application Service Provider
 *
 * Responsible for:
 * - Registering model observers
 * - Bootstrapping enums
 * - Registering application-wide events & listeners
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services by registering model observers.
     *
     * Attaches observers for Eloquent models used by the application (for example, Section and Widget).
     */
    public function boot(): void
    {
        $this->registerObservers();
    }

    /**
     * Attach observers to Eloquent models so their lifecycle events are handled.
     *
     * Specifically registers SectionObserver for App\Models\Regions\Section and
     * WidgetObserver for App\Models\Regions\Widget.
     */
    protected function registerObservers(): void
    {
        Section::observe(
            new SectionObserver(new SectionAdapter())
        );

        Widget::observe(
            new WidgetObserver(new WidgetAdapter())
        );
    }
}