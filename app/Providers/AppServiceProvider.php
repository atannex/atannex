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
     * Bootstrap application services.
     */
    public function boot(): void
    {
        $this->registerObservers();
    }

    /**
     * Register model observers.
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
