<?php

namespace Atannex\Filters;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

/**
 * Trait GetCreation
 *
 * Provides reusable methods for managing view files associated with models.
 * Supports creation, updating, and deletion of view files with configurable paths and extensions.
 */
trait GetCreation
{
    /**
     * The base path for storing view files (e.g., 'views/widgets').
     *
     * @var string
     */
    protected string $viewPath = 'views/widgets';

    /**
     * The file extension for view files (e.g., '.blade.php').
     *
     * @var string
     */
    protected string $fileExtension = '.blade.php';

    /**
     * Create a view file for the given model.
     *
     * @param Model $model The model instance
     * @param bool $force Overwrite existing file if true
     * @return void
     */
    public function createView(Model $model, bool $force = false): void
    {
        $this->ensureDirectoryExists();
        $path = $this->getViewPath($model->slug);

        if (!File::exists($path) || $force) {
            File::put($path, $this->getViewContent($model));
        }
    }

    /**
     * Update the view file when the model's slug changes.
     *
     * @param Model $model The model instance
     * @param string $oldSlug The previous slug
     * @return void
     */
    public function updateView(Model $model, string $oldSlug): void
    {
        $oldPath = $this->getViewPath($oldSlug);
        $newPath = $this->getViewPath($model->slug);

        if (File::exists($oldPath) && $oldPath !== $newPath) {
            File::move($oldPath, $newPath);
        }

        $this->createView($model);
    }

    /**
     * Delete the view file associated with the given slug.
     *
     * @param string $slug The slug of the view file
     * @return void
     */
    public function deleteView(string $slug): void
    {
        $path = $this->getViewPath($slug);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    /**
     * Ensure the directory for view files exists.
     *
     * @return void
     */
    protected function ensureDirectoryExists(): void
    {
        $dir = resource_path($this->viewPath);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
    }

    /**
     * Get the full file path for a view based on the slug.
     *
     * @param string $slug The slug to generate the file path
     * @return string The full file path
     */
    protected function getViewPath(string $slug): string
    {
        return resource_path("{$this->viewPath}/" . Str::slug($slug) . $this->fileExtension);
    }

    /**
     * Generate the content for the view file (empty).
     *
     * @param Model $model The model instance
     * @return string The content to write to the view file
     */
    protected function getViewContent(Model $model): string
    {
        return ''; // Empty content
    }

    /**
     * Set a custom view path for the trait.
     *
     * @param string $viewPath The custom view path
     * @return void
     */
    public function setViewPath(string $viewPath): void
    {
        $this->viewPath = rtrim($viewPath, '/');
    }

    /**
     * Set a custom file extension for the view files.
     *
     * @param string $extension The file extension (e.g., '.php', '.blade.php')
     * @return void
     */
    public function setFileExtension(string $extension): void
    {
        $this->fileExtension = $extension;
    }
}
