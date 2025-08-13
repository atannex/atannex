<?php

namespace App\Console\Commands;

use App\Models\Posts\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdatePostPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:post-paths {--batch-size=200 : Number of posts to process per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the slug_path for all posts based on their category paths';

    /**
     * Batch size for processing posts.
     *
     * @var int
     */
    protected $batchSize;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $this->initializeCommand();
            return $this->processPosts();
        } catch (Throwable $e) {
            $this->handleException($e);
            return self::FAILURE;
        }
    }

    /**
     * Initialize command settings and configurations.
     */
    private function initializeCommand(): void
    {
        $this->batchSize = (int) $this->option('batch-size');
        $this->info('Starting post path update process...');
        Log::info('Starting post path update process', [
            'batch_size' => $this->batchSize
        ]);
    }

    /**
     * Process posts and update their slug paths.
     *
     * @return int
     */
    private function processPosts(): int
    {
        $updatedCount = 0;
        $posts = Post::with('category')->get();

        $this->output->progressStart($posts->count());

        DB::beginTransaction();

        try {
            foreach ($posts->chunk($this->batchSize) as $postBatch) {
                foreach ($postBatch as $post) {
                    if ($this->updatePostPath($post)) {
                        $updatedCount++;
                    }
                    $this->output->progressAdvance();
                }
            }

            DB::commit();
            $this->output->progressFinish();
            $this->displayResults($updatedCount);

            return self::SUCCESS;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update the slug path for a single post.
     *
     * @param Post $post
     * @return bool
     */
    private function updatePostPath(Post $post): bool
    {
        if (!$post->category || !$post->slug) {
            Log::warning('Skipping post path update due to missing category or slug', [
                'post_id' => $post->id
            ]);
            return false;
        }

        $newSlugPath = rtrim($post->category->slug_path, '/') . '/' . $post->slug;

        if ($post->slug_path !== $newSlugPath) {
            $post->slug_path = $newSlugPath;
            $post->saveQuietly();
            Log::debug('Updated post path', [
                'post_id' => $post->id,
                'new_path' => $newSlugPath
            ]);
            return true;
        }

        return false;
    }

    /**
     * Display the results of the update process.
     *
     * @param int $updatedCount
     */
    private function displayResults(int $updatedCount): void
    {
        $message = "✅ Successfully updated {$updatedCount} post paths.";
        $this->info($message);
        Log::info($message);
    }

    /**
     * Handle any exceptions that occur during command execution.
     *
     * @param Throwable $e
     */
    private function handleException(Throwable $e): void
    {
        $errorMessage = 'Failed to update post paths: ' . $e->getMessage();
        $this->error($errorMessage);
        Log::error($errorMessage, [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}
