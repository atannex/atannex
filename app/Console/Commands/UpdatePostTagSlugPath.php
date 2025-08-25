<?php

namespace App\Console\Commands;

use App\Models\Pivots\PostTag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdatePostTagSlugPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posttag:update-slug-path {--batch-size=100 : Number of post_tag records to process per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the slug_path column on the post_tag pivot table based on post category and tag slugs';

    /**
     * Batch size for processing post_tag records.
     *
     * @var int
     */
    protected $batchSize;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->initializeCommand();
            return $this->processPostTags();
        } catch (Throwable $throwable) {
            $this->handleException($throwable);
            return self::FAILURE;
        }
    }

    /**
     * Initialize command settings and configurations.
     */
    private function initializeCommand(): void
    {
        $this->batchSize = (int) $this->option('batch-size');
        $this->info('Starting post_tag slug path update process...');
        Log::info('Starting post_tag slug path update process', [
            'batch_size' => $this->batchSize
        ]);
    }

    /**
     * Process post_tag records and update their slug paths.
     */
    private function processPostTags(): int
    {
        $updatedCount = 0;
        $postTags = PostTag::with(['post.category', 'tag'])->get();

        $this->output->progressStart($postTags->count());

        DB::beginTransaction();

        try {
            foreach ($postTags->chunk($this->batchSize) as $postTagBatch) {
                foreach ($postTagBatch as $postTag) {
                    if ($this->updatePostTagPath($postTag)) {
                        $updatedCount++;
                    }

                    $this->output->progressAdvance();
                }
            }

            DB::commit();
            $this->output->progressFinish();
            $this->displayResults($updatedCount);

            return self::SUCCESS;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }
    }

    /**
     * Update the slug path for a single post_tag record.
     */
    private function updatePostTagPath(PostTag $postTag): bool
    {
        $post = $postTag->post;
        $tag = $postTag->tag;

        if (!$tag) {
            Log::warning('Skipping post_tag path update due to missing tag', [
                'post_tag_id' => $postTag->id,
                'post_id' => $post?->id
            ]);
            if ($postTag->slug_path !== null) {
                $postTag->slug_path = null;
                $postTag->saveQuietly();
                return true;
            }

            return false;
        }

        $newSlugPath = $post && $post->category
            ? rtrim($post->category->slug_path, '/') . '/' . $tag->slug
            : $tag->slug;

        if ($postTag->slug_path !== $newSlugPath) {
            $postTag->slug_path = $newSlugPath;
            $postTag->saveQuietly();
            Log::debug('Updated post_tag path', [
                'post_tag_id' => $postTag->id,
                'post_id' => $post?->id,
                'tag_id' => $tag->id,
                'new_path' => $newSlugPath
            ]);
            return true;
        }

        return false;
    }

    /**
     * Display the results of the update process.
     */
    private function displayResults(int $updatedCount): void
    {
        $message = sprintf('✅ Successfully updated %d post_tag slug paths.', $updatedCount);
        $this->info($message);
        Log::info($message);
    }

    /**
     * Handle any exceptions that occur during command execution.
     */
    private function handleException(Throwable $e): void
    {
        $errorMessage = 'Failed to update post_tag slug paths: ' . $e->getMessage();
        $this->error($errorMessage);
        Log::error($errorMessage, [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}
