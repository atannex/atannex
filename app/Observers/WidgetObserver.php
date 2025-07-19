<?php

namespace App\Observers;

use App\Models\Pages\Widget;
use Lebialem\Adapters\Widget as AdaptersWidget;

class WidgetObserver
{
    /**
     * Create a new observer instance.
     *
     * @param AdaptersWidget $viewService The manager for handling widget view operations
     */
    public function __construct(protected readonly AdaptersWidget $viewService) {}

    /**
     * Handle the Widget "created" event.
     *
     * @param Widget $widget The widget model instance
     * @return void
     */
    public function created(Widget $widget): void
    {
        $this->viewService->createView($widget);
    }

    /**
     * Handle the Widget "updated" event.
     *
     * @param Widget $widget The widget model instance
     * @return void
     */
    public function updated(Widget $widget): void
    {
        if ($widget->wasChanged('slug')) {
            $originalSlug = $widget->getOriginal('slug');
            $this->viewService->updateView($widget, $originalSlug);
        } else {
            $this->viewService->createView($widget);
        }
    }

    /**
     * Handle the Widget "deleted" event.
     *
     * @param Widget $widget The widget model instance
     * @return void
     */
    public function deleted(Widget $widget): void
    {
        $this->viewService->deleteView($widget->slug);
    }

    /**
     * Handle the Widget "force deleted" event.
     *
     * @param Widget $widget The widget model instance
     * @return void
     */
    public function forceDeleted(Widget $widget): void
    {
        $this->viewService->deleteView($widget->slug);
    }
}
