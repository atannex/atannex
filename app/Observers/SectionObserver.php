<?php

namespace App\Observers;

use App\Models\Regions\Section;
use Atannex\Adapters\SectionAdapter;

class SectionObserver
{
    protected readonly SectionAdapter $viewService;

    /**
     * Create a new observer instance.
     *
     * @param SectionAdapter $viewService The manager for handling section view operations
     */
    public function __construct(SectionAdapter $viewService)
    {

        $this->viewService = $viewService;
    }

    /**
     * Handle the Section "created" event.
     *
     * @param Section $section The section model instance
     */
    public function created(Section $section): void
    {
        $this->viewService->createView($section);
    }

    /**
     * Handle the Section "updated" event.
     *
     * @param Section $section The section model instance
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
     */
    public function deleted(Section $section): void
    {
        $this->viewService->deleteView($section->slug);
    }

    /**
     * Handle the Section "force deleted" event.
     *
     * @param Section $section The section model instance
     */
    public function forceDeleted(Section $section): void
    {
        $this->viewService->deleteView($section->slug);
    }
}
