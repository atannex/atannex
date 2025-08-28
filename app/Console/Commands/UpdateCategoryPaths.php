<?php

namespace App\Console\Commands;

use App\Models\Pages\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateCategoryPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atannex:update-category-paths {--batch-size=100 : Number of categories to process per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the full_path for all categories based on their parent category hierarchy';

    /**
     * Batch size for processing categories.
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
            return $this->processCategories();
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
        $this->info('Starting category path update process...');
        Log::info('Starting category path update process', [
            'batch_size' => $this->batchSize
        ]);
    }

    /**
     * Process categories and update their paths.
     */
    private function processCategories(): int
    {
        $updatedCount = 0;
        $categories = Category::orderBy('parent_id')->get();

        $this->output->progressStart($categories->count());

        DB::beginTransaction();

        try {
            foreach ($categories->chunk($this->batchSize) as $categoryBatch) {
                foreach ($categoryBatch as $category) {
                    $slugPath = $this->buildSlugPath($category);

                    if ($category->slug_path !== $slugPath) {
                        $category->slug_path = $slugPath;
                        $category->saveQuietly();
                        $updatedCount++;
                        Log::debug('Updated category path', [
                            'category_id' => $category->id,
                            'new_path' => $slugPath
                        ]);
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
     * Build the slug path for a category based on its hierarchy.
     */
    private function buildSlugPath(Category $category): string
    {
        return $category->parent
            ? rtrim($category->parent->slug_path, '/') . '/' . $category->slug
            : $category->slug;
    }

    /**
     * Display the results of the update process.
     */
    private function displayResults(int $updatedCount): void
    {
        $message = sprintf('✅ Successfully updated %d category paths.', $updatedCount);
        $this->info($message);
        Log::info($message);
    }

    /**
     * Handle any exceptions that occur during command execution.
     */
    private function handleException(Throwable $e): void
    {
        $errorMessage = 'Failed to update category paths: ' . $e->getMessage();
        $this->error($errorMessage);
        Log::error($errorMessage, [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}
