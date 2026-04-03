<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Atannex\Foundation\Concerns\CanGenerateCode;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class GenerateFoundationCodes extends Command
{
    protected $signature = 'generate:codes
        {--all : Process all discovered models}
        {--force : Force regeneration of existing codes}
        {--with-trashed : Include soft-deleted records}
        {--chunk=200 : Chunk size for processing}
        {--retries=5 : Max retry attempts per record}
        {--silent : Minimal output}';

    protected $description = 'Generate unique codes for models using CanGenerateCode trait';

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
                $this->warn('No models found using CanGenerateCode trait.');
                return self::FAILURE;
            }

            $this->processModels();

            return $this->displayReport();
        } catch (Throwable $e) {
            $this->error($e->getMessage());
            Log::critical('Code generation command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return self::FAILURE;
        }
    }

    /* -----------------------------------------------------------------
     |  DISCOVERY
     |-----------------------------------------------------------------*/

    private function discoverModels(): void
    {
        $paths = [
            app_path('Models') => 'App\\Models',
            app_path()         => 'App',
        ];

        foreach ($paths as $path => $namespace) {
            if (is_dir($path)) {
                $this->scanDirectory($path, $namespace);
            }
        }

        $this->models = $this->models->unique()->values();
    }

    private function scanDirectory(string $path, string $namespace): void
    {
        foreach (scandir($path) ?: [] as $file) {
            if (str_starts_with($file, '.')) {
                continue;
            }

            $fullPath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($fullPath)) {
                $this->scanDirectory($fullPath, $namespace . '\\' . ucfirst($file));
                continue;
            }

            if (!str_ends_with($file, '.php')) {
                continue;
            }

            $class = $namespace . '\\' . Str::replaceLast('.php', '', $file);

            if (class_exists($class) && $this->usesCanGenerateCode($class)) {
                $this->models->push($class);
            }
        }
    }

    private function usesCanGenerateCode(string $class): bool
    {
        try {
            return in_array(
                CanGenerateCode::class,
                class_uses_recursive($class) ?: [],
                true
            );
        } catch (Throwable) {
            return false;
        }
    }

    /* -----------------------------------------------------------------
     |  PROCESSING
     |-----------------------------------------------------------------*/

    private function processModels(): void
    {
        foreach ($this->models as $modelClass) {
            $this->processModel($modelClass);
        }
    }

    private function processModel(string $modelClass): void
    {
        $model = new $modelClass();

        if (!$this->isValidModel($model)) {
            $this->warn("Skipping " . class_basename($modelClass) . " (missing required methods)");
            return;
        }

        $codeColumn = $model->getCodeColumn();
        $query = $this->buildQuery($modelClass, $codeColumn);

        $total = $query->count();

        if ($total === 0) {
            $this->line(class_basename($modelClass) . ": nothing to process");
            return;
        }

        $this->info("Processing " . class_basename($modelClass) . " ({$total} records)");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $success = 0;

        $query->chunkById((int) $this->option('chunk'), function (Collection $records) use (&$success, $bar, $modelClass) {
            foreach ($records as $record) {
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
        });

        $bar->finish();
        $this->newLine(2);

        $this->stats[$modelClass] = [
            'total'     => $total,
            'success'   => $success,
            'failed'    => $total - $success,
        ];
    }

    private function processRecord(Model $record): void
    {
        $force = (bool) $this->option('force');

        if ($force) {
            $record->regenerateCode();
        } else {
            $record->applyCodeIfNeeded();
        }

        // Safe save with retry on unique violations
        $retries = (int) $this->option('retries');

        for ($i = 0; $i < $retries; $i++) {
            try {
                $record->saveQuietly();
                return;
            } catch (Throwable $e) {
                if ($this->isUniqueViolation($e) && $i < $retries - 1) {
                    $record->regenerateCode();   // regenerate on collision
                    continue;
                }
                throw $e;
            }
        }
    }

    private function buildQuery(string $modelClass, string $codeColumn): Builder
    {
        $query = $modelClass::query();

        if ($this->option('with-trashed') && $this->usesSoftDeletes($modelClass)) {
            $query->withTrashed();
        }

        if (!$this->option('force')) {
            $query->whereNull($codeColumn);
        }

        return $query;
    }

    /* -----------------------------------------------------------------
     |  HELPERS
     |-----------------------------------------------------------------*/

    private function isValidModel(Model $model): bool
    {
        return method_exists($model, 'applyCodeIfNeeded')
            && method_exists($model, 'regenerateCode')
            && method_exists($model, 'getCodeColumn');
    }

    private function usesSoftDeletes(string $modelClass): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($modelClass) ?: [], true);
    }

    private function isUniqueViolation(Throwable $e): bool
    {
        $message = strtoupper($e->getMessage());
        return str_contains($message, 'UNIQUE')
            || str_contains($message, 'DUPLICATE')
            || in_array($e->getCode() ?? '', ['23000', '23505'], true);
    }

    /* -----------------------------------------------------------------
     |  REPORTING
     |-----------------------------------------------------------------*/

    private function displayReport(): int
    {
        $this->info('--- Code Generation Report ---');

        foreach ($this->stats as $model => $stat) {
            $this->line(sprintf(
                "%s → Total: %d | Success: %d | Failed: %d",
                class_basename($model),
                $stat['total'],
                $stat['success'],
                $stat['failed']
            ));
        }

        if ($this->failures->isNotEmpty()) {
            $this->error("Failures: " . $this->failures->count());

            $this->failures->take(10)->each(function ($failure) {
                $this->line(
                    class_basename($failure['model']) .
                        "#{$failure['id']} → {$failure['error']}"
                );
            });

            return self::FAILURE;
        }

        $this->info('All codes generated successfully.');
        return self::SUCCESS;
    }
}
