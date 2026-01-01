<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posts\Post;

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
     * Execute the console command.
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
