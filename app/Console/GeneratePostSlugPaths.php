<?php

namespace App\Console;

use App\Models\Posts\Post;
use Illuminate\Console\Command;

class GeneratePostSlugPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atannex:generate-post-slug-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slug_path for all posts, force updating if necessary';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting slug_path generation for posts...');

        Post::with('category', 'tags')->chunk(50, function ($posts) {
            foreach ($posts as $post) {
                $base = $post->getSlugBase() ?? '';
                $newSlugPath = trim($base.'/'.$post->slug, '/');

                // Force update by checking uniqueness and appending counter if needed
                $post->slug_path = $this->generateUniqueSlugPath($post, $newSlugPath);

                $post->saveQuietly();

                // Update related tags' slug_paths
                $post->cascadeSlugPathUpdates();
            }
        });

        $this->info('Slug path generation completed successfully.');
    }

    /**
     * Generate a unique slug path, force updating if necessary.
     */
    protected function generateUniqueSlugPath(Post $post, string $slugPath): string
    {
        $original = $slugPath;
        $counter = 1;

        while (Post::where('slug_path', $slugPath)
            ->where('id', '!=', $post->id)
            ->exists()
        ) {
            $slugPath = $original.'-'.$counter;
            $counter++;
        }

        return $slugPath;
    }
}
