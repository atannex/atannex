<?php

namespace App\Console\Commands;

use Throwable;
use App\Enums\Flag;
use App\Models\Posts\Post;
use App\Events\PostPublished;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atannex:publish-scheduled-posts {--batch-size=100 : Number of posts to process per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes scheduled posts that have reached their publication time.';

    /**
     * Maximum number of posts to process per batch (default value).
     *
     * @var int
     */
    protected $defaultBatchSize = 100;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $batchSize = $this->option('batch-size') ?? $this->defaultBatchSize;

            $totalPublished = $this->processScheduledPosts($batchSize);

            if ($totalPublished === 0) {
                $this->info('✅ No scheduled posts to publish at this time.');
                Log::info('No scheduled posts found for publication');
                return Command::SUCCESS;
            }

            $this->info("✅ Successfully published {$totalPublished} scheduled post(s).");
            Log::info('Scheduled posts publication completed', ['total_published' => $totalPublished]);

            return Command::SUCCESS;
        } catch (Throwable $e) {
            $this->error("❌ Error publishing scheduled posts: {$e->getMessage()}");
            Log::error('Failed to publish scheduled posts', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Process scheduled posts in batches.
     *
     * @param int $batchSize
     * @return int Total number of posts published
     */
    protected function processScheduledPosts(int $batchSize): int
    {
        $totalPublished = 0;

        Post::query()
            ->where('flag', Flag::SCHEDULED)
            ->where('scheduled_at', '<=', now())
            ->chunk($batchSize, function (Collection $posts) use (&$totalPublished) {
                DB::beginTransaction();
                try {
                    foreach ($posts as $post) {
                        $this->publishPost($post);
                        $totalPublished++;
                    }
                    DB::commit();
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::error('Failed to publish batch of posts', [
                        'error' => $e->getMessage(),
                        'post_ids' => $posts->pluck('id')->toArray(),
                    ]);
                    $this->error("⚠️ Failed to publish batch: {$e->getMessage()}");
                }
            });

        return $totalPublished;
    }

    /**
     * Publish a single post and log the action.
     *
     * @param Post $post
     * @return void
     */
    protected function publishPost(Post $post): void
    {
        try {
            $post->update([
                'flag' => Flag::PUBLISHED,
                'published_at' => $post->published_at ?? now(),
                'updated_at' => now(),
            ]);

            $this->line("📰 Published Post [ID: {$post->id}] - '{$post->title}'");
            Log::info('Post published successfully', [
                'id' => $post->id,
                'title' => $post->title,
                'published_at' => $post->published_at->toISOString(),
            ]);

            $this->dispatchPostPublishedEvent($post);
        } catch (Throwable $e) {
            Log::warning('Failed to publish individual post', [
                'id' => $post->id,
                'title' => $post->title,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Dispatch post published event.
     *
     * @param Post $post
     * @return void
     */
    protected function dispatchPostPublishedEvent(Post $post): void
    {
        try {
            event(new PostPublished($post));
            Log::debug('Post published event dispatched successfully', [
                'post_id' => $post->id,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to dispatch post published event', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
