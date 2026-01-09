<?php

namespace App\Providers;

use App\Models\Modules\PostModule;
use Illuminate\Support\ServiceProvider;
use App\Models\Regions\Widget;
use App\Models\Regions\Section;
use App\Observers\PostModuleObserver;
use App\Observers\WidgetObserver;
use App\Observers\SectionObserver;
use Atannex\Adapters\WidgetAdapter;
use Atannex\Adapters\SectionAdapter;

/**
 * Application Service Provider
 *
 * Responsibilities:
 * - Register all model observers
 * - Keep observer wiring centralized and predictable
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
     * Centralized observer registration.
     */
    protected function registerObservers(): void
    {
        Section::observe(new SectionObserver(new SectionAdapter()));
        Widget::observe(new WidgetObserver(new WidgetAdapter()));
        PostModule::observe(PostModuleObserver::class);
    }
}
