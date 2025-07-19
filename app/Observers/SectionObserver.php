<?php

namespace App\Observers;

use App\Models\Pages\Section;
use Lebialem\Adapters\Section as AdaptersSection;

class SectionObserver
{
    /**
     * Create a new observer instance.
     *
     * @param AdaptersSection $viewService The manager for handling section view operations
     */
    public function __construct(protected readonly AdaptersSection $viewService) {}

    /**
     * Handle the Section "created" event.
     *
     * @param Section $section The section model instance
     * @return void
     */
    public function created(Section $section): void
    {
        $this->viewService->createView($section);
    }

    /**
     * Handle the Section "updated" event.
     *
     * @param Section $section The section model instance
     * @return void
     */
    public function updated(Section $section): void
    {
        if ($section->wasChanged('slug')) {
            $originalSlug = $section->getOriginal('slug');
            $this->viewService->updateView($section, $originalSlug);
        } else {
            $this->viewService->createView($section);
        }
    }

    /**
     * Handle the Section "deleted" event.
     *
     * @param Section $section The section model instance
     * @return void
     */
    public function deleted(Section $section): void
    {
        $this->viewService->deleteView($section->slug);
    }

    /**
     * Handle the Section "force deleted" event.
     *
     * @param Section $section The section model instance
     * @return void
     */
    public function forceDeleted(Section $section): void
    {
        $this->viewService->deleteView($section->slug);
    }
}
