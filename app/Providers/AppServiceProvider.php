<?php

namespace App\Providers;

use App\Enums\Flag;
use App\Enums\Image;
use App\Enums\Title;
use App\Enums\Entity;
use App\Enums\Status;
use App\Enums\Binding;
use App\Enums\Classification;
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
        $this->bootEnums();
        $this->registerEventListeners();
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

    /**
     * Boot all enum classes.
     */
    protected function bootEnums(): void
    {
        Flag::boot();
        Entity::boot();
        Image::boot();
        Binding::boot();
        Status::boot();
        Title::boot();
        Classification::boot();
    }

    /**
     * Register application event listeners.
     */
    protected function registerEventListeners(): void
    {

    }
}
