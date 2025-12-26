<?php

namespace App\Console\Commands;

use App\Models\Docs\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateDocumentSlugPaths extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'documents:update-slug-paths {--dry-run : Show changes without saving}';

    /**
     * The console command description.
     */
    protected $description = 'Update slug_path for documents using type/slug format';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $this->info('Updating document slug paths...');
        $this->newLine();

        Document::query()
            ->select(['id', 'type', 'slug', 'slug_path'])
            ->whereNotNull('type')
            ->whereNotNull('slug')
            ->chunkById(200, function ($documents) use ($dryRun) {
                foreach ($documents as $document) {
                    $newSlugPath = Str::lower(
                        trim($document->type, '/') . '/' . trim($document->slug, '/')
                    );

                    if ($document->slug_path === $newSlugPath) {
                        continue;
                    }

                    $this->line(sprintf(
                        '[%s] %s → %s',
                        $document->id,
                        $document->slug_path ?? 'NULL',
                        $newSlugPath
                    ));

                    if (! $dryRun) {
                        $document->updateQuietly([
                            'slug_path' => $newSlugPath,
                        ]);
                    }
                }
            });

        $this->newLine();
        $this->info(
            $dryRun
                ? 'Dry run complete. No records were updated.'
                : 'Slug paths successfully updated.'
        );

        return self::SUCCESS;
    }
}
