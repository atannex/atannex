<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posts\Post;

class UpdatePostDatePath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:update-date-path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the date_path column for all posts based on published_at';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Updating date_path for all posts...');

        Post::chunk(100, function ($posts) {
            foreach ($posts as $post) {
                if ($post->published_at) {
                    $post->date_path = $post->published_at->format('m/Y');
                    $post->saveQuietly(); // Avoid triggering events if unnecessary
                }
            }
        });

        $this->info('All posts updated successfully!');
        return 0;
    }
}
