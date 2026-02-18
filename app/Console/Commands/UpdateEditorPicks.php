<?php

namespace App\Console\Commands;

use App\Models\Posts\Post;
use Illuminate\Console\Command;

class UpdateEditorPicks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atannex:update-editor-picks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate expired editor picks';

    /**
     * Deactivate expired editor picks on posts.
     *
     * Updates posts where `is_editor_pick` is true and `editor_pick_expires` is set and
     * less than or equal to the current time, setting `is_editor_pick` to `false`.
     * After updating, writes an informational message to the console.
     */
    public function handle()
    {
        // Deactivate expired editor picks
        Post::where('is_editor_pick', true)
            ->whereNotNull('editor_pick_expires')
            ->where('editor_pick_expires', '<=', now())
            ->update(['is_editor_pick' => false]);

        $this->info('Expired editor picks deactivated.');
    }
}
