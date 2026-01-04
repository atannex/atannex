<?php

namespace App\Providers;

use App\Models\Modules\PostModule;
use App\Models\User;
use App\Models\Others\About;
use Illuminate\Support\ServiceProvider;
use App\Models\Others\Gallery;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Regions\Ruler;
use App\Models\Regions\Widget;
use App\Models\Regions\Section;
use App\Observers\ImageObserver;
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
     * Bootstrap application services by registering model observers.
     *
     * Attaches observers for Eloquent models used by the application (for example, Section and Widget).
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
     * Models requiring automatic image cleanup.
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
    }
}
