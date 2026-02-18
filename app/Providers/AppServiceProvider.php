<?php

namespace App\Providers;

use App\Models\Modules\PostModule;
use App\Models\Regions\Section;
use App\Models\Regions\Widget;
use App\Observers\PostModuleObserver;
use App\Observers\SectionObserver;
use App\Observers\WidgetObserver;
use Atannex\Adapters\SectionAdapter;
use Atannex\Adapters\WidgetAdapter;
use Illuminate\Support\ServiceProvider;

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
     * Register model observers used by the application.
     *
     * Registers observers for Eloquent models that require lifecycle handling, such as image cleanup and Section/Widget observers.
     */
    public function boot(): void
    {
        $this->registerObservers();
    }

    /**
    /**
     * Register model observers used by the application.
     *
     * Attaches the ImageObserver to each model returned by imageCleanupModels(), registers a SectionObserver
     * (constructed with a SectionAdapter) for the Section model, and registers a WidgetObserver
     * (constructed with a WidgetAdapter) for the Widget model.
     */
    protected function registerObservers(): void
    {
        PostModule::observe(PostModuleObserver::class);
        Section::observe(new SectionObserver(new SectionAdapter));
        Widget::observe(new WidgetObserver(new WidgetAdapter));
    }
}
