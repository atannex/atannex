<?php

namespace App\Providers;

use App\Enums\Flag;
use App\Enums\Icons;
use App\Enums\Image;
use App\Enums\Binding;
use App\Enums\PostType;
use App\Models\Pages\Widget;
use App\Models\Pages\Section;
use App\Observers\WidgetObserver;
use App\Observers\SectionObserver;
use Ngangagah\Handlers\Navigation;
use Atannex\Adapters\WidgetAdapter;
use Atannex\Adapters\SectionAdapter;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 *
 * Registers and bootstraps application services, including:
 *  - Observers registration for Page, Widget, and Section models
 *  - Bootstrapping enum classes
 *  - Sharing global navigation data with all views
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * Registers model observers, boots enums, and shares navigation data with all views.
     *
     * @param Navigation $navigation Navigation handler for shared navigation data
     */
    public function boot(): void
    {
        $this->registerObservers();
        $this->bootEnums();
    }

    /**
     * Register model observers.
     */
    protected function registerObservers(): void
    {
        Section::observe(new SectionObserver(new SectionAdapter()));
        Widget::observe(new WidgetObserver(new WidgetAdapter()));
    }

    /**
     * Boot enum classes.
     */
    protected function bootEnums(): void
    {
        Flag::boot();
        PostType::boot();
        Binding::boot();
        Icons::boot();
        Image::boot();
    }
}
