<?php

namespace App\Console\Commands;

use App\Models\Regions\Region;
use Illuminate\Console\Command;

class RefreshRegionSlugPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example usage:
     * php artisan regions:refresh-slug-paths
     */
    protected $signature = 'regions:refresh-slug-paths';

    /**
     * The console command description.
     */
    protected $description = 'Rebuilds the slug_path column for all regions based on hierarchy.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Refreshing region slug paths...');

        // Process in chunks to avoid memory overload
        Region::with('parent', 'children')->chunk(100, function ($regions) {
            foreach ($regions as $region) {
                // Use the new public method
                $region->rebuildSlugPath();
                $region->saveQuietly(); // avoid firing events endlessly

                // Recursively fix children
                $region->cascadeSlugPathUpdates();
            }
        });

        $this->info('✅ Region slug paths refreshed successfully.');

        return Command::SUCCESS;
    }
}
