<?php

namespace Atannex\Adapters;

use Atannex\Filters\ViewFileManager;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SectionAdapter
 *
 * Manages view file operations for "Section" models.
 * Provides reusable logic for creating, updating, and deleting
 * Blade view files associated with dynamic sections.
 */
final class SectionAdapter
{
    use ViewFileManager;

    /**
     * Initialize the adapter with custom view directory and file extension.
     */
    public function __construct()
    {
        $this->setViewDirectory('views/sections');
        $this->setViewExtension('.blade.php');
    }

    /**
     * Create or update a section view file.
     */
    public function generateSectionView(Model $section, bool $force = false): void
    {
        $this->createView($section, $force);
    }

    /**
     * Delete a section view file by slug.
     */
    public function removeSectionView(string $slug): void
    {
        $this->deleteView($slug);
    }
}
