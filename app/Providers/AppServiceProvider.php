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

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerObservers();
    }

    protected function registerObservers(): void
    {
        PostModule::observe(PostModuleObserver::class);
        Section::observe(new SectionObserver(new SectionAdapter));
        Widget::observe(new WidgetObserver(new WidgetAdapter));
    }
}
