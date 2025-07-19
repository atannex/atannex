<?php

namespace App\Console\Commands;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atannex:publish-scheduled-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish posts that are scheduled and whose scheduled time has arrived';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $posts = Post::where('flag', Flag::SCHEDULED)
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($posts->isEmpty()) {
            $this->info('No scheduled posts to publish.');
            return 0;
        }

        DB::transaction(function () use ($posts) {
            foreach ($posts as $post) {
                $post->flag = Flag::PUBLISHED;
                $post->published_at = $post->published_at ?? now();
                $post->save();

                $this->info("Published post ID {$post->id} titled '{$post->title}'.");
                Log::info("Scheduled post published: ID {$post->id}, Title: {$post->title}");
            }
        });

        return 0;
    }
}
