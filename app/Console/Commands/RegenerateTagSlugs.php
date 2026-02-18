<?php

namespace App\Console\Commands;

use App\Models\Tags\Tag;
use Illuminate\Console\Command;

class RegenerateTagSlugs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tags:regenerate-slugs
                            {--force : Regenerate slugs even if they already exist}';

    /**
     * The console command description.
     */
    protected $description = 'Regenerate unique slugs for all tags in the database.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("Starting slug regeneration for tags...\n");

        $processed = 0;

        Tag::withTrashed()->chunk(200, function ($tags) use (&$processed) {

            foreach ($tags as $tag) {

                if (! $this->option('force') && ! empty($tag->slug)) {
                    continue;
                }

                $tag->ensureSlug();
                $tag->save();

                $processed++;
                $this->line("Updated slug for Tag ID {$tag->id}");
            }
        });

        $this->info("\nSlug regeneration complete.");
        $this->info("Total tags updated: {$processed}");

        return Command::SUCCESS;
    }
}
