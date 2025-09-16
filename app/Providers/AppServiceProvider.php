<?php

namespace App\Providers;

use App\Enums\Binding;
use App\Enums\Flag;
use App\Enums\Icons;
use App\Enums\Image;
use App\Enums\Entity;
use App\Enums\Territories;
use App\Models\Posts\Post;
use App\Models\Pages\Widget;
use App\Models\Pages\Section;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Region;
use App\Observers\WidgetObserver;
use App\Observers\SectionObserver;
use Ngangagah\Handlers\Navigation;
use Atannex\Adapters\WidgetAdapter;
use App\Observers\SluggableObserver;
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

        Category::observe(SluggableObserver::class);
        Region::observe(SluggableObserver::class);
        PostTag::observe(SluggableObserver::class);
        Post::observe(SluggableObserver::class);
    }

    /**
     * Boot enum classes.
     */
    protected function bootEnums(): void
    {
        Flag::boot();
        Entity::boot();
        Image::boot();
        Territories::boot();
        Binding::boot();
        Icons::boot();
    }
}
