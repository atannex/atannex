<?php

namespace App\Console\Commands;

use App\Models\Posts\Post;
use Illuminate\Console\Command;

class UpdatePostPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan update:post-paths
     */
    protected $signature = 'update:post-paths';

    /**
     * The console command description.
     */
    protected $description = 'Update slug_path for all posts based on their category paths';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Updating post full_path values...");

        $updatedCount = 0;

        // Eager load category to avoid N+1 queries
        Post::with('category')->chunk(200, function ($posts) use (&$updatedCount) {
            foreach ($posts as $post) {
                if ($post->category && $post->slug) {
                    $post->slug_path = $post->category->slug_path . '/' . $post->slug;
                    $post->saveQuietly(); // Save without firing events to optimize
                    $updatedCount++;
                }
            }
        });

        $this->info("✅ Updated {$updatedCount} posts.");
    }
}
