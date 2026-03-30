<?php

declare(strict_types=1);

namespace Atannex\Foundation\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

/**
 * CanGenerateCode Trait
 *
 * Enterprise-grade code generation for Eloquent models with support for:
 * - Automatic unique code generation
 * - Configurable prefixes, formats, and random segments
 * - Relationship-based source resolution (dot notation)
 * - Immutability controls
 * - Caching for performance optimization
 * - Comprehensive error handling and logging
 *
 * @uses Model
 */
trait CanGenerateCode
{
    /* -----------------------------------------------------------------
     |  LIFECYCLE HOOKS
     |-----------------------------------------------------------------*/

    protected static function bootCanGenerateCode(): void
    {
        static::creating(fn(Model $model) => $model->applyGeneratedCode());
        static::updating(fn(Model $model) => $model->handleCodeOnUpdate());
        static::restoring(fn(Model $model) => $model->handleCodeOnRestore());
        static::deleting(fn(Model $model) => $model->handleCodeOnDelete());
    }

    /* -----------------------------------------------------------------
     |  PUBLIC ACCESSORS
     |-----------------------------------------------------------------*/

    /**
     * Get the code column name.
     *
     * @return string
     */
    public function resolveCodeColumn(): string
    {
        return $this->getCodeColumn();
    }

    /**
     * Get the complete code configuration.
     *
     * @return array<string, string|int>
     */
    public function resolveCodeConfig(): array
    {
        return [
            'column' => $this->getCodeColumn(),
            'source' => $this->getCodeSourceColumn(),
            'prefix' => $this->getCodePrefix(),
        ];
    }

    /* -----------------------------------------------------------------
     |  CONFIGURATION METHODS
     |-----------------------------------------------------------------*/

    /**
     * Get the database column for storing generated codes.
     *
     * @return string
     */
    protected function getCodeColumn(): string
    {
        return $this->codeColumn ?? 'code';
    }

    /**
     * Get the source column or dot-notation path for code generation.
     *
     * @return string
     */
    protected function getCodeSourceColumn(): string
    {
        return $this->codeSourceColumn ?? 'name';
    }

    /**
     * Get the static prefix for all generated codes.
     *
     * @return string
     */
    protected function getCodePrefix(): string
    {
        return $this->codePrefix ?? 'ATA';
    }

    /**
     * Get the date format for the year component.
     *
     * @return string
     */
    protected function getCodeYearFormat(): string
    {
        return $this->codeYearFormat ?? 'y';
    }

    /**
     * Get the length of the abbreviation component.
     *
     * @return int
     */
    protected function getCodeAbbreviationLength(): int
    {
        return max(1, (int) ($this->codeAbbreviationLength ?? 2));
    }

    /**
     * Get the number of random digits in the code.
     *
     * @return int
     */
    protected function getCodeRandomDigits(): int
    {
        return max(1, (int) ($this->codeRandomLength ?? 4));
    }

    /**
     * Get the maximum number of generation attempts before throwing.
     *
     * @return int
     */
    protected function getMaxGenerationAttempts(): int
    {
        return max(1, (int) ($this->codeMaxAttempts ?? 12));
    }

    /**
     * Determine if generated codes are immutable (cannot be changed).
     *
     * @return bool
     */
    protected function isCodeImmutable(): bool
    {
        return $this->codeImmutable ?? true;
    }

    /**
     * Determine if codes should be regenerated when source changes.
     *
     * @return bool
     */
    protected function shouldRegenerateOnUpdate(): bool
    {
        return $this->regenerateCodeOnUpdate ?? true;
    }

    /**
     * Get the base query builder for uniqueness checks.
     * Override this to customize scoping (e.g., by tenant).
     *
     * @return Builder
     */
    protected function getUniquenessQuery(): Builder
    {
        return $this->newQuery();
    }

    /**
     * Get the cache key for code uniqueness checks.
     *
     * @param string $code
     * @return string
     */
    protected function getCodeCacheKey(string $code): string
    {
        return sprintf('%s::%s::%s', static::class, $this->getCodeColumn(), $code);
    }

    /**
     * Determine if caching is enabled for uniqueness checks.
     *
     * @return bool
     */
    protected function shouldCacheCodeLookups(): bool
    {
        return $this->cacheCodeLookups ?? false;
    }

    /**
     * Get the cache duration for code uniqueness checks (in minutes).
     *
     * @return int
     */
    protected function getCodeCacheDuration(): int
    {
        return max(1, (int) ($this->codeRecheckTime ?? 5));
    }

    /* -----------------------------------------------------------------
     |  CORE LOGIC
     |-----------------------------------------------------------------*/

    /**
     * Apply a generated code to the model.
     *
     * @param bool $force Force regeneration even if code exists
     * @return void
     * @throws RuntimeException
     */
    public function applyGeneratedCode(bool $force = false): void
    {
        $column = $this->getCodeColumn();

        if (!$force && !empty($this->getAttribute($column))) {
            return;
        }

        $sourceValue = $this->getCodeSourceValue();
        $abbreviation = $this->createAbbreviation($sourceValue);
        $generatedCode = $this->generateUniqueCode($abbreviation);

        $this->setAttribute($column, $generatedCode);
    }

    /**
     * Handle code logic during model updates.
     *
     * @return void
     * @throws RuntimeException
     */
    protected function handleCodeOnUpdate(): void
    {
        if ($this->isCodeImmutable()) {
            return;
        }

        if (!$this->shouldRegenerateOnUpdate()) {
            return;
        }

        if ($this->hasSourceChanged()) {
            $this->applyGeneratedCode(true);
        }
    }

    /**
     * Handle code logic during model restoration (soft deletes).
     *
     * @return void
     * @throws RuntimeException
     */
    protected function handleCodeOnRestore(): void
    {
        $column = $this->getCodeColumn();

        if (empty($this->getAttribute($column))) {
            $this->applyGeneratedCode(true);
        }
    }

    /**
     * Handle code logic during model deletion.
     * Extension hook for custom logic (e.g., archival).
     *
     * @return void
     */
    protected function handleCodeOnDelete(): void
    {
        // Override in models that need custom deletion logic
    }

    /**
     * Detect if the source value has changed (supports dot notation and relationships).
     *
     * @return bool
     */
    protected function hasSourceChanged(): bool
    {
        $path = $this->getCodeSourceColumn();

        // Simple column - check dirty state
        if (!str_contains($path, '.')) {
            return $this->isDirty($path);
        }

        // Relationship-based - always regenerate for safety
        return true;
    }

    /**
     * Generate a unique code with retry logic and caching.
     *
     * @param string $abbreviation
     * @return string
     * @throws RuntimeException
     */
    protected function generateUniqueCode(string $abbreviation): string
    {
        $prefix = $this->getCodePrefix();
        $year = now()->format($this->getCodeYearFormat());
        $normalizedAbbr = $this->normalizeAbbreviation($abbreviation);
        $randomLength = $this->getCodeRandomDigits();
        $maxAttempts = $this->getMaxGenerationAttempts();
        $column = $this->getCodeColumn();

        $cache = $this->shouldCacheCodeLookups();
        $cacheDuration = $this->getCodeCacheDuration();

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $candidate = $prefix . $year . $normalizedAbbr . $this->generateRandomNumericString($randomLength);

            // Check cache first if enabled
            if ($cache && $this->isCodeCachedAsUnique($candidate, $cacheDuration)) {
                continue;
            }

            // Check database
            if (!$this->codeExistsInDatabase($candidate, $column)) {
                // Cache the code as unique if caching is enabled
                if ($cache) {
                    $this->cacheCodeAsUnique($candidate, $cacheDuration);
                }

                return $candidate;
            }
        }

        throw new RuntimeException(
            sprintf('Failed to generate unique code after %d attempts for %s', $maxAttempts, static::class)
        );
    }

    /**
     * Check if a code exists in the database.
     *
     * @param string $code
     * @param string $column
     * @return bool
     */
    private function codeExistsInDatabase(string $code, string $column): bool
    {
        return $this->getUniquenessQuery()
            ->where($column, $code)
            ->exists();
    }

    /**
     * Check if a code is cached as unique.
     *
     * @param string $code
     * @param int $duration
     * @return bool
     */
    private function isCodeCachedAsUnique(string $code, int $duration): bool
    {
        return Cache::has($this->getCodeCacheKey($code));
    }

    /**
     * Cache a code as unique.
     *
     * @param string $code
     * @param int $duration Minutes
     * @return void
     */
    private function cacheCodeAsUnique(string $code, int $duration): void
    {
        Cache::put($this->getCodeCacheKey($code), true, now()->addMinutes($duration));
    }

    /* -----------------------------------------------------------------
     |  SOURCE RESOLUTION (DOT NOTATION SUPPORT)
     |-----------------------------------------------------------------*/

    /**
     * Get the source value for code generation.
     * Supports dot notation for relationship traversal.
     *
     * @return string
     */
    protected function getCodeSourceValue(): string
    {
        $path = $this->getCodeSourceColumn();

        if (!str_contains($path, '.')) {
            return (string) $this->getAttribute($path);
        }

        return (string) ($this->resolvePath($path) ?? '');
    }

    /**
     * Resolve a dot-notation path through model attributes and relationships.
     *
     * @param string $path Dot-notation path (e.g., 'relation.name')
     * @return mixed
     */
    protected function resolvePath(string $path): mixed
    {
        $segments = explode('.', $path);
        $value = $this;

        foreach ($segments as $segment) {
            $value = $this->resolveSegment($value, $segment);

            if ($value === null) {
                return null;
            }
        }

        return $value;
    }

    /**
     * Resolve a single segment in a dot-notation path.
     *
     * @param mixed $value
     * @param string $segment
     * @return mixed
     */
    private function resolveSegment(mixed $value, string $segment): mixed
    {
        if ($value instanceof Model) {
            return $this->resolveModelSegment($value, $segment);
        }

        if (is_array($value)) {
            return $value[$segment] ?? null;
        }

        if (is_object($value)) {
            return $value->{$segment} ?? null;
        }

        return null;
    }

    /**
     * Resolve a segment for a Model instance.
     *
     * @param Model $model
     * @param string $segment
     * @return mixed
     */
    private function resolveModelSegment(Model $model, string $segment): mixed
    {
        // Check if relation is already loaded
        if ($model->relationLoaded($segment)) {
            return $model->getRelation($segment);
        }

        // Check if method exists (e.g., accessor)
        if (method_exists($model, $segment)) {
            return $model->{$segment};
        }

        // Fall back to attribute
        return $model->getAttribute($segment);
    }

    /* -----------------------------------------------------------------
     |  HELPERS
     |-----------------------------------------------------------------*/

    /**
     * Create an abbreviation from text.
     * Takes the first letter of each word up to the configured length.
     *
     * @param string $text
     * @return string
     */
    protected function createAbbreviation(string $text): string
    {
        // Remove non-alphanumeric characters
        $text = trim(preg_replace('/[^A-Za-z0-9\s]/', '', $text) ?? '');

        if ($text === '') {
            return 'XX';
        }

        // Split into words
        $words = preg_split('/\s+/', $text) ?: [];

        $abbreviation = '';
        $targetLength = $this->getCodeAbbreviationLength();

        // Build abbreviation from first letter of each word
        foreach ($words as $word) {
            if (isset($word[0])) {
                $abbreviation .= strtoupper($word[0]);

                if (strlen($abbreviation) >= $targetLength) {
                    break;
                }
            }
        }

        return $abbreviation ?: 'XX';
    }

    /**
     * Normalize abbreviation to the configured length.
     * Pads with 'X' if too short, truncates if too long.
     *
     * @param string $abbreviation
     * @return string
     */
    protected function normalizeAbbreviation(string $abbreviation): string
    {
        $length = $this->getCodeAbbreviationLength();

        return str_pad(
            strtoupper(substr($abbreviation, 0, $length)),
            $length,
            'X',
            STR_PAD_RIGHT
        );
    }

    /**
     * Generate a random numeric string of specified length.
     * Ensures first digit is never 0 for multi-digit strings.
     *
     * @param int $length
     * @return string
     */
    protected function generateRandomNumericString(int $length): string
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= random_int(0, 9);
        }

        // Ensure first digit is not 0 for multi-digit numbers
        if ($length > 1 && $result[0] === '0') {
            $result[0] = (string) random_int(1, 9);
        }

        return $result;
    }
}
