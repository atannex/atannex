<?php

namespace App\Providers;

use App\Enums\Binding;
use App\Enums\Classification;
use App\Enums\Entity;
use App\Enums\Flag;
use App\Enums\Image;
use App\Enums\Status;
use App\Enums\Title;
use App\Models\Regions\Section;
use App\Models\Regions\Widget;
use App\Observers\SectionObserver;
use App\Observers\WidgetObserver;
use Atannex\Adapters\SectionAdapter;
use Atannex\Adapters\WidgetAdapter;
use Illuminate\Support\ServiceProvider;
use Ngangagah\Handlers\Navigation;

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
     * @param  Navigation  $navigation  Navigation handler for shared navigation data
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
        Section::observe(new SectionObserver(new SectionAdapter));
        Widget::observe(new WidgetObserver(new WidgetAdapter));
    }

    /**
     * Boot enum classes.
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
}
