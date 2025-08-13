<?php

namespace App\Console\Commands;

use App\Models\Pages\Category;
use Illuminate\Console\Command;

class UpdateCategoryPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan update:category-paths
     */
    protected $signature = 'update:category-paths';

    /**
     * The console command description.
     */
    protected $description = 'Update full_path for all categories based on their parent categories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating category slug_path values...');

        $updatedCount = 0;

        // Load all categories ordered by parent_id to help build paths bottom-up
        $categories = Category::orderBy('parent_id')->get();

        foreach ($categories as $category) {
            $slugPath = $category->parent
                ? $category->parent->slug_path . '/' . $category->slug
                : $category->slug;

            // Only update if changed to minimize unnecessary queries
            if ($category->slug_path !== $slugPath) {
                $category->slug_path = $slugPath;
                $category->saveQuietly();
                $updatedCount++;
            }
        }

        $this->info("✅ Updated {$updatedCount} categories.");
    }
}
