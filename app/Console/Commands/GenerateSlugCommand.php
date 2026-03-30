<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * GenerateSlugCommand - Interactive Edition
 *
 * Professional command for interactive bulk slug generation.
 *
 * Features:
 * - Auto-discovery of all models using GeneratesSlug trait
 * - Interactive model selection with statistics
 * - Configurable generation options with prompts
 * - Real-time progress tracking
 * - Comprehensive error handling with recovery
 * - Detailed reporting and logging
 *
 * Usage:
 *   php artisan generate:slugs              # Interactive mode
 *   php artisan generate:slugs --all        # Process all at once
 *   php artisan generate:slugs --batch      # Batch mode
 *
 * @see GeneratesSlug
 */
class GenerateSlugCommand extends Command
{
    protected $signature = 'generate:slugs
        {--all : Process all discovered models without prompts}
        {--batch : Run in batch mode (quiet)}
        {--force : Force regeneration of existing slugs}
        {--chunk=200 : Batch processing chunk size}
        {--memory=512M : PHP memory limit}
        {--retries=3 : Max retry attempts}
        {--silent : Disable all interactive prompts}';

    protected $description = 'Interactive slug generation for models using GeneratesSlug trait';

    /**
     * State Management
     */
    private Collection $discoveredModels;
    private Collection $selectedModels;
    private array $options;
    private array $statistics = [];
    private Collection $failures;

    public function __construct()
    {
        parent::__construct();
        $this->discoveredModels = collect();
        $this->selectedModels = collect();
        $this->failures = collect();
    }

    public function handle(): int
    {
        try {
            return $this->executeCommand();
        } catch (Throwable $e) {
            $this->reportFatalError($e);
            return self::FAILURE;
        }
    }

    /* -----------------------------------------------------------------
     |  MAIN EXECUTION FLOW
     |-----------------------------------------------------------------*/

    private function executeCommand(): int
    {
        $this->initializeEnvironment();
        $this->displayWelcomeMessage();

        // Step 1: Discover models
        $this->discoverAllModels();
        if ($this->discoveredModels->isEmpty()) {
            $this->error('No models using GeneratesSlug trait found.');
            return self::FAILURE;
        }

        // Step 2: Select models
        if (!$this->selectModels()) {
            $this->warn('No models selected.');
            return self::FAILURE;
        }

        // Step 3: Configure options
        $this->configureOptions();

        // Step 4: Display configuration summary
        $this->displayConfigurationSummary();

        // Step 5: Confirm and process
        if (!$this->confirmProcessing()) {
            $this->comment('Operation cancelled.');
            return self::SUCCESS;
        }

        // Step 6: Execute processing
        return $this->processSelectedModels();
    }

    private function initializeEnvironment(): void
    {
        $memoryLimit = (string) $this->option('memory');
        if (@ini_set('memory_limit', $memoryLimit) === false) {
            $this->warn("Unable to set memory limit to {$memoryLimit}");
        }

        if ($this->option('batch')) {
            $this->output->setVerbosity(OutputStyle::VERBOSITY_QUIET);
        }
    }

    private function displayWelcomeMessage(): void
    {
        if ($this->option('silent') || $this->option('batch')) {
            return;
        }

        $this->newLine();
        $this->info('╔════════════════════════════════════════════════════════╗');
        $this->info('║            Atangageih L. Collins                       ║');
        $this->info('║        Slug Generator - Interactive Mode               ║');
        $this->info('║            ATANNEX - BELLAH NGANGAGAH                  ║');
        $this->info('╚════════════════════════════════════════════════════════╝');
        $this->newLine();
    }

    /* -----------------------------------------------------------------
     |  MODEL DISCOVERY
     |-----------------------------------------------------------------*/

    private function discoverAllModels(): void
    {
        $this->info('🔍 Discovering models with GeneratesSlug trait...');
        $this->info('   Scanning all PHP files recursively...');

        // Try multiple common root paths
        $searchPaths = [
            app_path('Models') => 'App\\Models',
            app_path() => 'App',
        ];

        $discovered = collect();

        foreach ($searchPaths as $path => $baseNamespace) {
            if (!is_dir($path)) {
                continue;
            }

            $found = $this->scanDirectoryRecursive($path, $baseNamespace)
                ->filter(fn(string $class) => class_exists($class))
                ->filter(fn(string $class) => $this->usesTrait($class))
                ->values();

            $discovered = $discovered->merge($found);
        }

        $this->discoveredModels = $discovered->unique()->values();

        if ($this->discoveredModels->isNotEmpty()) {
            $this->newLine();
            $this->line("<fg=green>✓</> Found <fg=green>{$this->discoveredModels->count()}</> model(s):");
            $this->discoveredModels->each(fn(string $class) => $this->line("   • " . $class));
            $this->newLine();
        } else {
            $this->newLine();
            $this->error('❌ No models found with GeneratesSlug trait');
            $this->newLine();
            $this->line('<fg=yellow>Troubleshooting:</> Make sure your models have the trait:');
            $this->line('');
            $this->line('   <fg=cyan>use GeneratesSlug;</fg>');
            $this->line('   <fg=cyan>use Atannex\\Foundation\\Concerns\\GeneratesSlug;</fg>');
            $this->line('');
            $this->line('Example model structure:');
            $this->line('');
            $this->line('   <fg=cyan>app/Models/Post.php</>');
            $this->line('   <fg=cyan>app/Models/Products/Category.php</>');
            $this->line('   <fg=cyan>app/Models/Admin/Settings.php</>');
            $this->newLine();
        }
    }

    /**
     * Recursively scan directory for PHP files
     */
    private function scanDirectoryRecursive(string $basePath, string $baseNamespace): Collection
    {
        $classes = collect();
        $this->scanPath($basePath, $baseNamespace, $classes);
        return $classes;
    }

    /**
     * Recursively scan a path and collect all PHP files
     */
    private function scanPath(string $path, string $namespace, Collection &$classes): void
    {
        $files = @scandir($path) ?: [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || str_starts_with($file, '.')) {
                continue;
            }

            $fullPath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($fullPath)) {
                $subNamespace = $namespace . '\\' . ucfirst($file);
                $this->scanPath($fullPath, $subNamespace, $classes);
                continue;
            }

            if (is_file($fullPath) && str_ends_with($file, '.php')) {
                $className = Str::replaceLast('.php', '', $file);
                $fullClassName = $namespace . '\\' . $className;
                $classes->push($fullClassName);
            }
        }
    }

    private function usesTrait(string $class): bool
    {
        try {
            $traits = class_uses_recursive($class) ?: [];
            return in_array(GeneratesSlug::class, $traits, true);
        } catch (Throwable) {
            return false;
        }
    }

    /* -----------------------------------------------------------------
     |  MODEL SELECTION
     |-----------------------------------------------------------------*/

    private function selectModels(): bool
    {
        // Batch or all flag: auto-select all
        if ($this->option('batch') || $this->option('all') || $this->option('silent')) {
            $this->selectedModels = $this->discoveredModels;
            return $this->selectedModels->isNotEmpty();
        }

        // Interactive: let user choose
        $selected = $this->choice(
            'Select models to process',
            array_merge(
                $this->discoveredModels->map(fn($c) => class_basename($c))->all(),
                ['All', 'Cancel']
            ),
            multiple: true
        );

        if (in_array('Cancel', $selected)) {
            return false;
        }

        if (in_array('All', $selected)) {
            $this->selectedModels = $this->discoveredModels;
        } else {
            $this->selectedModels = $this->discoveredModels->filter(
                fn(string $class) => in_array(class_basename($class), $selected)
            )->values();
        }

        return $this->selectedModels->isNotEmpty();
    }

    /* -----------------------------------------------------------------
     |  CONFIGURATION
     |-----------------------------------------------------------------*/

    private function configureOptions(): void
    {
        // In batch/silent mode, use defaults or command-line options
        if ($this->option('batch') || $this->option('silent')) {
            $this->options = [
                'force' => (bool) $this->option('force'),
                'chunk_size' => max(1, (int) $this->option('chunk')),
                'max_retries' => max(1, (int) $this->option('retries')),
            ];
            return;
        }

        // Interactive: ask for options
        $this->newLine();
        $this->info('⚙️  Configure Generation Options:');
        $this->newLine();

        // Force regeneration?
        $this->options['force'] = (bool) $this->option('force') ||
            $this->confirm('Force regenerate existing slugs?', false);

        // Chunk size
        $this->options['chunk_size'] = (int) $this->ask(
            'Chunk size for batch processing',
            (string) $this->option('chunk')
        );

        // Retry attempts
        $this->options['max_retries'] = (int) $this->ask(
            'Maximum retry attempts',
            (string) $this->option('retries')
        );

        $this->newLine();
    }

    /* -----------------------------------------------------------------
     |  CONFIGURATION SUMMARY
     |-----------------------------------------------------------------*/

    private function displayConfigurationSummary(): void
    {
        $this->newLine();
        $this->info('📋 Configuration Summary:');
        $this->newLine();

        // Models
        $this->line('<fg=cyan>Models to Process:</> ' . $this->selectedModels->count());
        $this->selectedModels->each(fn($class) => $this->line('   • ' . class_basename($class)));

        // Options
        $this->newLine();
        $this->line('<fg=cyan>Options:</fg>');
        $this->line('   • Force Regenerate: ' . ($this->options['force'] ? '<fg=yellow>Yes</>' : 'No'));
        $this->line('   • Chunk Size: ' . $this->options['chunk_size']);
        $this->line('   • Max Retries: ' . $this->options['max_retries']);

        $this->newLine();
    }

    private function confirmProcessing(): bool
    {
        if ($this->option('batch') || $this->option('silent')) {
            return true;
        }

        return $this->confirm('Proceed with slug generation?', true);
    }

    /* -----------------------------------------------------------------
     |  MAIN PROCESSING
     |-----------------------------------------------------------------*/

    private function processSelectedModels(): int
    {
        $this->newLine();
        $this->info('🚀 Starting slug generation...');
        $this->newLine();

        foreach ($this->selectedModels as $modelClass) {
            $this->processModel($modelClass);
        }

        return $this->displayFinalReport();
    }

    private function processModel(string $modelClass): void
    {
        try {
            /** @var Model&GeneratesSlug $model */
            $model = new $modelClass();

            if (!$this->validateModel($model, $modelClass)) {
                return;
            }

            $slugColumn = $this->getSlugColumn($model);
            $query = $this->buildQuery($modelClass, $slugColumn);
            $count = $query->count();

            if ($count === 0) {
                $this->line("<fg=gray>⊘</> " . class_basename($modelClass) . ": No records to process");
                return;
            }

            $this->processBatch($query, $modelClass, $count);
        } catch (Throwable $e) {
            $this->error("✗ Failed to process " . class_basename($modelClass) . ": " . $e->getMessage());
            Log::error("GenerateSlugCommand failed", [
                'model' => $modelClass,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function validateModel(Model $model, string $modelClass): bool
    {
        if (!method_exists($model, 'generateSlugIfNeeded')) {
            $this->warn("⚠ Skipping " . class_basename($modelClass) . " (invalid trait)");
            return false;
        }

        return true;
    }

    private function getSlugColumn(Model $model): string
    {
        if (method_exists($model, 'getSlugConfig')) {
            return $model->getSlugConfig('column', 'slug');
        }

        return 'slug';
    }

    private function buildQuery(string $modelClass, string $slugColumn): Builder
    {
        $query = $modelClass::query();

        if (!$this->options['force']) {
            $query->whereNull($slugColumn);
        }

        return $query;
    }

    private function processBatch(Builder $query, string $modelClass, int $count): void
    {
        $modelName = class_basename($modelClass);
        $this->info("Processing {$modelName} ({$count} records)");

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        $successful = 0;

        $query->chunkById($this->options['chunk_size'], function (Collection $records) use (
            &$successful,
            $progressBar,
            $modelClass
        ) {
            foreach ($records as $record) {
                try {
                    $this->processRecord($record);
                    $successful++;
                } catch (Throwable $e) {
                    $this->failures->push([
                        'model' => $modelClass,
                        'id' => $record->getKey(),
                        'error' => $e->getMessage(),
                    ]);
                }

                $progressBar->advance();
            }
        });

        $progressBar->finish();

        $this->statistics[$modelClass] = [
            'total' => $count,
            'successful' => $successful,
            'failed' => $count - $successful,
        ];

        $this->newLine(2);
    }

    private function processRecord(Model $record): void
    {
        for ($attempt = 0; $attempt < $this->options['max_retries']; $attempt++) {
            try {
                DB::beginTransaction();

                $record->generateSlugIfNeeded();

                if (method_exists($record, 'persistWithSlug')) {
                    $record->persistWithSlug();
                } else {
                    $record->saveQuietly();
                }

                DB::commit();
                return;
            } catch (Throwable $e) {
                DB::rollBack();

                if ($this->isUniqueConstraintViolation($e) && $attempt < $this->options['max_retries'] - 1) {
                    usleep((100 * pow(2, $attempt)) * 1000);
                    continue;
                }

                throw $e;
            }
        }
    }

    /* -----------------------------------------------------------------
     |  HELPERS
     |-----------------------------------------------------------------*/

    private function isUniqueConstraintViolation(Throwable $e): bool
    {
        $message = strtoupper($e->getMessage());
        return str_contains($message, 'UNIQUE')
            || str_contains($message, 'DUPLICATE')
            || str_contains($message, 'CONSTRAINT');
    }

    /* -----------------------------------------------------------------
     |  REPORTING
     |-----------------------------------------------------------------*/

    private function displayFinalReport(): int
    {
        $this->newLine();
        $this->info('═══════════════════════════════════════════════════════');
        $this->info('Generation Report');
        $this->info('═══════════════════════════════════════════════════════');
        $this->newLine();

        $totalProcessed = collect($this->statistics)->sum('total');
        $totalSuccessful = collect($this->statistics)->sum('successful');

        // Models statistics
        $this->line('<fg=cyan>Models Processed:</> ' . count($this->statistics));
        foreach ($this->statistics as $model => $stats) {
            $modelName = class_basename($model);
            $this->line("   <fg=cyan>•</> {$modelName}");
            $this->line("      Total: {$stats['total']} | Success: <fg=green>{$stats['successful']}</> | Failed: <fg=red>{$stats['failed']}</>");
        }

        // Overall statistics
        $this->newLine();
        $this->line('<fg=cyan>Overall Statistics:</fg>');
        $this->line('   Total Records: ' . $totalProcessed);
        $this->line('   Successful: <fg=green>✓ ' . $totalSuccessful . '</>');

        if ($this->failures->isNotEmpty()) {
            $this->line('   Failures: <fg=red>✗ ' . $this->failures->count() . '</>');

            if ($this->output->isVerbose() && $this->failures->isNotEmpty()) {
                $this->newLine();
                $this->line('<fg=cyan>Failure Details:</fg>');
                $this->failures->take(10)->each(
                    fn($f) =>
                    $this->line("      ✗ " . class_basename($f['model']) . "#{$f['id']} → {$f['error']}")
                );

                if ($this->failures->count() > 10) {
                    $this->line("      ... and " . ($this->failures->count() - 10) . " more");
                }
            }
        }

        $this->newLine();
        $this->info('═══════════════════════════════════════════════════════');
        $this->newLine();

        return $this->failures->isEmpty() ? self::SUCCESS : self::FAILURE;
    }

    private function reportFatalError(Throwable $e): void
    {
        $this->error('Fatal Error: ' . $e->getMessage());

        Log::critical("GenerateSlugCommand fatal error", [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        if ($this->output->isVerbose()) {
            $this->line($e->getTraceAsString());
        }
    }
}
