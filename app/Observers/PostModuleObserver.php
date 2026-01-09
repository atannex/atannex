<?php

namespace App\Observers;

use App\Models\Modules\PostModule;
use Illuminate\Support\Facades\Storage;
/**
 * Observer for the PostModule model.
 *
 * Responsibilities:
 * 1. Automatically manages file cleanup when a PostModule is updated or deleted.
 * 2. Tracks files referenced in the module's content blocks (images, videos, side-by-side layouts, etc.).
 * 3. Detects changes in video-related fields and removes old files from storage.
 * 4. Ensures no orphaned files remain in the public storage disk.
 *
 * Key Components:
 * - $blockFileMap: Maps block types to the keys that store file paths.
 * - $videoFields: List of nested video fields to monitor for changes and deletion.
 * - updating(): Deletes old content files that are no longer used.
 * - deleting(): Deletes all content files if the model is force deleted.
 * - extractFilePaths(): Utility to extract all file paths from content blocks.
 * - handleVideoFieldUpdates() & deleteVideoFieldFiles(): Manage video-specific file cleanup.
 *
 * Usage:
 * Register this observer in the PostModule model or service provider to handle file cleanup automatically
 * on model updates and deletions.
 */

class PostModuleObserver
{
    private array $blockFileMap = [
        'video_grid' => ['items', 'cover'],
        'video'      => 'cover',
        'image'      => 'src',
        'side-by-side' => 'image',
    ];

    private array $videoFields = [
        'video.path',
        'video.poster',
        'video.thumbnail',
    ];

    public function updating(PostModule $model): void
    {
        $oldFiles = $this->extractFilePaths($model->getOriginal('content'));
        $newFiles = $this->extractFilePaths($model->content);
        $this->deleteFiles(array_diff($oldFiles, $newFiles));

        $this->handleVideoFieldUpdates($model);
    }

    public function deleting(PostModule $model): void
    {
        if ($model->isForceDeleting()) {
            $files = $this->extractFilePaths($model->content);
            $this->deleteFiles($files);

            $this->deleteVideoFieldFiles($model->getAttributes());
        }
    }

    private function handleVideoFieldUpdates(PostModule $model): void
    {
        $original = $model->getOriginal();
        $current  = $model->getAttributes();

        foreach ($this->videoFields as $field) {
            $old = data_get($original, $field);
            $new = data_get($current, $field);

            if ($old && $old !== $new) {
                Storage::disk('public')->delete($old);
            }
        }
    }

    private function deleteVideoFieldFiles(array $data): void
    {
        foreach ($this->videoFields as $field) {
            $file = data_get($data, $field);
            if ($file) {
                Storage::disk('public')->delete($file);
            }
        }
    }

    private function extractFilePaths(array $content): array
    {
        $files = [];

        foreach ($content as $block) {
            $type = $block['type'];

            if (!empty($this->blockFileMap[$type])) {
                $mapping = $this->blockFileMap[$type];

                if (is_array($mapping)) {
                    [$subArray, $subKey] = $mapping;
                    $files = array_merge($files, array_column($block['data'][$subArray], $subKey));
                } else {
                    $files[] = $block['data'][$mapping];
                }
            }
        }

        return array_values(array_unique(array_filter($files)));
    }


    private function deleteFiles(array $files): void
    {
        foreach ($files as $file) {
            if ($file) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
