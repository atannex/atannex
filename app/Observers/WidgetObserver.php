<?php

namespace App\Observers;

use App\Models\Regions\Widget;
use Atannex\Adapters\WidgetAdapter;

class WidgetObserver
{
    /**
     * Create a new observer instance.
     *
     * @param WidgetAdapter $viewService The manager for handling widget view operations
     */
    public function __construct(protected readonly WidgetAdapter $viewService) {}

    /**
     * Handle the Widget "created" event.
     *
     * @param Widget $widget The widget model instance
     */
    public function created(WidgetAdapter $widget): void
    {
        $this->viewService->createView($widget);
    }

    /**
     * Handle the Widget "updated" event.
     *
     * @param Widget $widget The widget model instance
     */
    public function updated(WidgetAdapter $widget): void
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
     */
    public function deleted(WidgetAdapter $widget): void
    {
        $this->viewService->deleteView($widget->slug);
    }

    /**
     * Handle the Widget "force deleted" event.
     *
     * @param Widget $widget The widget model instance
     */
    public function forceDeleted(WidgetAdapter $widget): void
    {
        $this->viewService->deleteView($widget->slug);
    }
}
