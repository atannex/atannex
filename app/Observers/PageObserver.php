<?php

namespace App\Observers;

use App\Models\Pages\Page;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    public function created(Page $page): void
    {
        $this->clearPageCache($page);
    }

    public function updated(Page $page): void
    {
        $this->clearPageCache($page);
    }

    public function deleted(Page $page): void
    {
        $this->clearPageCache($page);
    }

    public function restored(Page $page): void
    {
        $this->clearPageCache($page);
    }

    protected function clearPageCache(Page $page): void
    {
        Cache::forget("page_{$page->slug}");
        Cache::forget('page_navigation_items');

        if (! $page->relationLoaded('sections')) {
            $page->load(['sections.widgets']);
        }

        foreach ($page->sections as $section) {
            Cache::forget("section_{$section->id}_content");

            foreach ($section->widgets as $widget) {
                Cache::forget("widget_{$widget->id}_content");

                foreach ($widget->pivot->config['tabs'] ?? [] as $tabConfig) {
                    $key = "tab_{$page->slug}_" . md5(json_encode($tabConfig));
                    Cache::forget($key);
                }
            }
        }
    }
}


