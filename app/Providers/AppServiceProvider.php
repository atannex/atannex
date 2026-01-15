<?php

namespace App\Providers;

use App\Events\ContactMessageSubmitted;
use App\Listeners\DispatchAdminContactEmails;
use App\Models\Modules\PostModule;
use Illuminate\Support\Facades\Event;
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
     * Register model observers used by the application.
     *
     * Registers observers for Eloquent models that require lifecycle handling, such as image cleanup and Section/Widget observers.
     */
    public function boot(): void
    {
        $this->registerObservers();
        Event::listen(ContactMessageSubmitted::class, DispatchAdminContactEmails::class);
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
        Section::observe(new SectionObserver(new SectionAdapter()));
        Widget::observe(new WidgetObserver(new WidgetAdapter()));
    }
}
