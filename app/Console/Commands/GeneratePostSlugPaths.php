<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posts\Post;

class GeneratePostSlugPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:generate-slug-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slug_path for all posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting slug_path generation for posts...');

        Post::with('category', 'tags')->chunk(50, function ($posts) {
            foreach ($posts as $post) {
                // Build slug_path using category slug_path + post slug
                $base = $post->getSlugBase() ?? '';
                $post->slug_path = trim($base . '/' . $post->slug, '/');

                $post->saveQuietly();

                // Update related tags' slug_paths
                $post->cascadeSlugPathUpdates();
            }
        });

        $this->info('slug_path generation completed successfully.');
    }
}
