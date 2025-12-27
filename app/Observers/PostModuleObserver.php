<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Modules\PostModule;
use App\Support\ClearsPostShowCache;

/**
 * Clears post show caches when a PostModule changes.
 */
final class PostModuleObserver
{
    use ClearsPostShowCache;

    public function created(PostModule $module): void
    {
        $this->clearFromModule($module);
    }

    public function updated(PostModule $module): void
    {
        $this->clearFromModule($module);
    }

    public function deleted(PostModule $module): void
    {
        $this->clearFromModule($module);
    }

    public function restored(PostModule $module): void
    {
        $this->clearFromModule($module);
    }

    private function clearFromModule(PostModule $module): void
    {
        $post = $module->post()->withTrashed()->first();

        if ($post) {
            $this->clearPostShowCache($post);
        }
    }
}
