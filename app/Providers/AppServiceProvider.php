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
<<<<<<< HEAD
        foreach ($this->imageCleanupModels() as $model) {
            $model::observe(ImageObserver::class);
        }

        Section::observe(
            new SectionObserver(new SectionAdapter())
        );

        Widget::observe(
            new WidgetObserver(new WidgetAdapter())
        );
    }

    /**
     * List model class names that require automatic image cleanup.
     *
     * @return string[] Fully-qualified model class names that require automatic image cleanup.
     */
    protected function imageCleanupModels(): array
    {
        return [
            Gallery::class,
            Region::class,
            Post::class,
            Category::class,
            About::class,
            PostModule::class,
            Ruler::class,
            User::class,
        ];
=======
        Section::observe(new SectionObserver(new SectionAdapter()));
        Widget::observe(new WidgetObserver(new WidgetAdapter()));
        PostModule::observe(PostModuleObserver::class);
>>>>>>> development
    }
}
