<?php

namespace App\Console\Commands;

use App\Models\Pages\Category;
use Illuminate\Console\Command;

class UpdateCategorySlugPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:update-slug-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update slug paths for all categories based on their hierarchy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting slug path update for categories...');

        $categories = Category::all();
        $total = $categories->count();
        $updated = 0;

        foreach ($categories as $category) {
            $category->slug_path = $category->getSlugPathAttribute();
            $category->saveQuietly();
            $updated++;
            $this->comment("Updated slug path for category: {$category->name} ({$category->slug_path})");
        }

        $this->info("Completed! Updated slug paths for {$updated} of {$total} categories.");
    }
}
