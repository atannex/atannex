<?php

namespace App\Providers;

use App\Enums\Binding;
use App\Enums\Flag;
use App\Enums\Icons;
use App\Enums\Image;
use App\Enums\PostType;
use App\Models\Pages\Page;
use App\Models\Pages\Widget;
use App\Models\Pages\Section;
use App\Observers\PageObserver;
use App\Observers\WidgetObserver;
use App\Observers\SectionObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Lebialem\Adapters\SectionAdapter;
use Lebialem\Adapters\WidgetAdapter;
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
     * Register any application services.
     *
     * Currently empty, but can be used to bind services, interfaces, or other container entries.
     *
     * @return void
     */
    public function register(): void
    {
        // Intentionally left blank
    }

    /**
     * Bootstrap any application services.
     *
     * Registers model observers, boots enums, and shares navigation data with all views.
     *
     * @param Navigation $navigation Navigation handler for shared navigation data
     * @return void
     */
    public function boot(Navigation $navigation): void
    {
        $this->registerObservers();
        $this->bootEnums();
        $this->shareNavigationData($navigation);
    }

    /**
     * Register model observers.
     *
     * @return void
     */
    protected function registerObservers(): void
    {
        Section::observe(new SectionObserver(new SectionAdapter()));
        Widget::observe(new WidgetObserver(new WidgetAdapter()));
        Page::observe(PageObserver::class);
    }

    /**
     * Boot enum classes.
     *
     * @return void
     */
    protected function bootEnums(): void
    {
        Flag::boot();
        PostType::boot();
        Binding::boot();
        Icons::boot();
        Image::boot();
    }

    /**
     * Share navigation data with all views.
     *
     * @param Navigation $navigation
     * @return void
     */
    protected function shareNavigationData(Navigation $navigation): void
    {
        View::share('global', $navigation->getPageNavigation());
    }
}
