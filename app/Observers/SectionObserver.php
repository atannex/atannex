<?php

namespace App\Observers;

use App\Models\Regions\Section;
use Atannex\Adapters\SectionAdapter;

class SectionObserver
{
    /**
     * Create a new observer instance.
     *
     * @param SectionAdapter $viewService The manager for handling section view operations
     */
    public function __construct(protected readonly SectionAdapter $viewService) {}

    /**
     * Handle the Section "created" event.
     *
     * @param Section $section The section model instance
     */
    public function created(SectionAdapter $section): void
    {
        $this->viewService->createView($section);
    }

    /**
     * Handle the Section "updated" event.
     *
     * @param Section $section The section model instance
     */
    public function updated(SectionAdapter $section): void
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
     */
    public function deleted(SectionAdapter $section): void
    {
        $this->viewService->deleteView($section->slug);
    }

    /**
     * Handle the Section "force deleted" event.
     *
     * @param Section $section The section model instance
     */
    public function forceDeleted(SectionAdapter $section): void
    {
        $this->viewService->deleteView($section->slug);
    }
}
