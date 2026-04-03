<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class GenerateSlugCommand extends Command
{
    protected $signature = 'generate:slugs
        {--all : Process all discovered models}
        {--force : Force regeneration of existing slugs}
        {--chunk=200 : Chunk size (fallback if needed)}
        {--retries=3 : Retry attempts for DB collisions}
        {--silent : Minimal output}';

    protected $description = 'Generate slugs for all models using GeneratesSlug trait';

    private Collection $models;
    private array $stats = [];
    private Collection $failures;

    public function __construct()
    {
        parent::__construct();
        $this->models = collect();
        $this->failures = collect();
    }

    public function handle(): int
    {
        try {
            $this->discoverModels();

            if ($this->models->isEmpty()) {
                $this->warn('No models found using GeneratesSlug trait.');
                return self::FAILURE;
            }

            $this->process();

            return $this->report();
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            Log::critical('Slug generation command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return self::FAILURE;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DISCOVERY
    |--------------------------------------------------------------------------
    */

    private function discoverModels(): void
    {
        $paths = [
            app_path('Models') => 'App\\Models',
            app_path()         => 'App',
        ];

        foreach ($paths as $path => $namespace) {
            if (is_dir($path)) {
                $this->scan($path, $namespace);
            }
        }

        $this->models = $this->models->unique()->values();
    }

    private function scan(string $path, string $namespace): void
    {
        foreach (scandir($path) ?: [] as $file) {
            if (str_starts_with($file, '.')) {
                continue;
            }

            $fullPath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($fullPath)) {
                $this->scan($fullPath, $namespace . '\\' . ucfirst($file));
                continue;
            }

            if (!str_ends_with($file, '.php')) {
                continue;
            }

            $className = $namespace . '\\' . Str::replaceLast('.php', '', $file);

            if (class_exists($className) && $this->usesSlugTrait($className)) {
                $this->models->push($className);
            }
        }
    }

    private function usesSlugTrait(string $class): bool
    {
        try {
            return in_array(
                GeneratesSlug::class,
                class_uses_recursive($class) ?: [],
                true
            );
        } catch (Throwable) {
            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESS
    |--------------------------------------------------------------------------
    */

    private function process(): void
    {
        foreach ($this->models as $modelClass) {
            $this->processModel($modelClass);
        }
    }

    private function processModel(string $modelClass): void
    {
        $model = new $modelClass();

        if (!$this->isValidSlugModel($model)) {
            $this->warn("Skipping " . class_basename($modelClass) . " (missing required slug methods)");
            return;
        }

        $slugColumn = $model->getSlugColumn();
        $query = $this->buildQuery($modelClass, $slugColumn);
        $total = $query->count();

        if ($total === 0) {
            $this->line(class_basename($modelClass) . ': nothing to process');
            return;
        }

        $this->info("Processing " . class_basename($modelClass) . " ({$total} records)");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $success = 0;

        foreach ($query->cursor() as $record) {
            try {
                $this->processRecord($record);
                $success++;
            } catch (Throwable $e) {
                $this->failures->push([
                    'model' => $modelClass,
                    'id'    => $record->getKey(),
                    'error' => $e->getMessage(),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->stats[$modelClass] = [
            'total'   => $total,
            'success' => $success,
            'failed'  => $total - $success,
        ];
    }

    private function processRecord(Model $record): void
    {
        if ($this->option('force')) {
            $record->regenerateSlug();
        } else {
            $record->generateSlugIfNeeded();
        }

        // Always use the trait's safe save method when available
        if (method_exists($record, 'saveWithSlugRetry')) {
            $record->saveWithSlugRetry((int) $this->option('retries'));
        } else {
            $record->saveQuietly();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function isValidSlugModel(Model $model): bool
    {
        return method_exists($model, 'generateSlugIfNeeded')
            && method_exists($model, 'regenerateSlug')
            && method_exists($model, 'getSlugColumn');
    }

    private function buildQuery(string $modelClass, string $slugColumn): \Illuminate\Database\Eloquent\Builder
    {
        $query = $modelClass::query();

        if (in_array(SoftDeletes::class, class_uses_recursive($modelClass))) {
            $query->withTrashed();
        }

        if (!$this->option('force')) {
            $query->whereNull($slugColumn)
                ->orWhere($slugColumn, '');
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | REPORT
    |--------------------------------------------------------------------------
    */

    private function report(): int
    {
        $this->info('--- Slug Generation Report ---');

        foreach ($this->stats as $model => $stat) {
            $this->line(sprintf(
                "%s → Total: %d | OK: %d | Fail: %d",
                class_basename($model),
                $stat['total'],
                $stat['success'],
                $stat['failed']
            ));
        }

        if ($this->failures->isNotEmpty()) {
            $this->error("Failures: " . $this->failures->count());
            $this->failures->take(10)->each(fn($f) => $this->line(
                class_basename($f['model']) . "#{$f['id']} → {$f['error']}"
            ));

            return self::FAILURE;
        }

        $this->info('All slugs generated successfully.');
        return self::SUCCESS;
    }
}
