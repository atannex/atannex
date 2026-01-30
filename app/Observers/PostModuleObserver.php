<?php

namespace App\Observers;

use App\Models\Modules\PostModule;
use Atannex\Traits\CleansUpContentFiles;

class PostModuleObserver
{
    use CleansUpContentFiles;

    public function updating(PostModule $model): void
    {
        $this->cleanupRemovedFiles(
            $model->getOriginal('content') ?? [],
            $model->content ?? []
        );
    }

    public function deleting(PostModule $model): void
    {
        /**
         * Soft-delete aware:
         * Only delete files on force delete
         */
        if (! $model->isForceDeleting()) {
            return;
        }

        $files = $this->extractFilePaths($model->content ?? []);
        $this->deleteFiles($files);
    }
}
