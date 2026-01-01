<?php

namespace Atannex\Filters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Trait ViewFileManager
 *
 * Provides reusable methods for managing view files associated with Eloquent models.
 * Handles creation, updating, and deletion of view files with customizable directories and file extensions.
 */
trait Manager
{
    /**
     * Base directory where view files are stored (e.g., 'views/widgets').
     */
    protected string $viewDirectory = 'views/widgets';

    /**
     * File extension for generated view files (e.g., '.blade.php').
     */
    protected string $viewExtension = '.blade.php';

    /**
     * Create a view file for the specified model.
     *
     * @param  bool  $force  Overwrite if file already exists.
     */
    public function createView(Model $model, bool $force = false): void
    {
        $this->ensureDirectoryExists();

        $path = $this->getViewFilePath($model->slug);

        if (! File::exists($path) || $force) {
            File::put($path, $this->generateViewContent($model));
        }
    }

    /**
     * Update the view file when the model's slug changes.
     */
    public function updateView(Model $model, string $oldSlug): void
    {
        $oldPath = $this->getViewFilePath($oldSlug);
        $newPath = $this->getViewFilePath($model->slug);

        if (File::exists($oldPath) && $oldPath !== $newPath) {
            File::move($oldPath, $newPath);
        }

        // Recreate the updated view content.
        $this->createView($model, true);
    }

    /**
     * Delete the view file for a given slug.
     */
    public function deleteView(string $slug): void
    {
        $path = $this->getViewFilePath($slug);

        if (File::exists($path)) {
            File::delete($path);
        }
    }

    /**
     * Ensure the directory for view files exists, creating it if necessary.
     */
    protected function ensureDirectoryExists(): void
    {
        $directory = resource_path($this->viewDirectory);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Get the full file path for the given slug.
     */
    protected function getViewFilePath(string $slug): string
    {
        return resource_path(
            sprintf('%s/%s%s', $this->viewDirectory, Str::slug($slug), $this->viewExtension)
        );
    }

    /**
     * Generate the content for the view file.
     * Can be overridden in consuming classes for custom templates.
     */
    protected function generateViewContent(Model $model): string
    {
        return ''; // Placeholder for dynamic content generation
    }

    /**
     * Set a custom directory path for storing view files.
     */
    public function setViewDirectory(string $directory): void
    {
        $this->viewDirectory = trim($directory, '/');
    }

    /**
     * Set a custom file extension for the view files.
     */
    public function setViewExtension(string $extension): void
    {
        $this->viewExtension = ltrim($extension, '.');
        $this->viewExtension = '.'.$this->viewExtension;
    }
}
