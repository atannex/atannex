<?php

namespace App\Observers;

use App\Models\Modules\PostModule;
use Illuminate\Support\Facades\Storage;

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

            if (!isset($this->blockFileMap[$type])) {
                continue;
            }

            $mapping = $this->blockFileMap[$type];

            if (is_array($mapping)) {
                [$subArray, $subKey] = $mapping;

                if (isset($block['data'][$subArray]) && is_array($block['data'][$subArray])) {
                    $files = array_merge($files, array_column($block['data'][$subArray], $subKey));
                }
            } else {
                if (isset($block['data'][$mapping])) {
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
