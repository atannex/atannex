<?php

declare(strict_types=1);

namespace Atannex\Foundation\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * GeneratesSlug
 *
 * Enterprise-grade slug generation with support for:
 * - Multiple generation strategies (word, random, mixed)
 * - Multi-column source aggregation
 * - Scoped uniqueness (tenant, parent, hierarchy)
 * - Collision-safe generation with retry logic
 * - Immutable and regenerating slug modes
 * - Custom transformation pipelines
 * - Database-level validation
 * - Comprehensive error handling and logging
 *
 * Usage:
 *   class Post extends Model {
 *       use GeneratesSlug;
 *
 *       protected array $slugConfig = [
 *           'column' => 'slug',
 *           'source' => ['title', 'description'],
 *           'mode' => 'word',
 *           'separator' => '-',
 *           'max_length' => 120,
 *           'immutable' => false,
 *       ];
 *   }
 *
 * @see https://docs.example.com/slug-generation
 */
trait GeneratesSlug
{
    /* -----------------------------------------------------------------
     |  LIFECYCLE HOOKS
     |-----------------------------------------------------------------*/

    public static function bootGeneratesSlug(): void
    {
        static::creating(fn(Model $model) => $model->generateSlugIfNeeded());
        static::updating(fn(Model $model) => $model->handleSlugOnUpdate());
    }

    /* -----------------------------------------------------------------
     |  PUBLIC INTERFACE
     |-----------------------------------------------------------------*/

    /**
     * Generate slug if needed
     *
     * @return void
     * @throws RuntimeException
     */
    public function generateSlugIfNeeded(): void
    {
        if ($this->isSlugLocked()) {
            return;
        }

        $slug = $this->generate();
        $this->setSlugAttribute($slug);
    }

    /**
     * Persist model with slug collision handling
     *
     * @return bool
     * @throws RuntimeException
     */
    public function persistWithSlug(): bool
    {
        return DB::transaction(function (): bool {
            try {
                return $this->save();
            } catch (QueryException $e) {
                if ($this->isUniqueConstraintViolation($e)) {
                    $this->handleUniqueConstraintViolation();
                    return $this->save();
                }

                Log::error('Slug persistence failed', [
                    'model' => static::class,
                    'error' => $e->getMessage(),
                ]);

                throw $e;
            }
        });
    }

    /**
     * Regenerate slug unconditionally
     *
     * @return void
     * @throws RuntimeException
     */
    public function regenerateSlug(): void
    {
        $slug = $this->generate();
        $this->setSlugAttribute($slug);
    }

    /**
     * Get configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSlugConfig(string $key, mixed $default = null): mixed
    {
        return $this->resolveSlugConfig()[$key] ?? $default;
    }

    /**
     * Resolve all slug configuration
     *
     * @return array
     */
    public function resolveSlugConfig(): array
    {
        return [
            'column' => $this->getSlugColumn(),
            'source' => $this->getSlugSource(),
            'mode' => $this->getSlugMode(),
            'separator' => $this->getSlugSeparator(),
            'max_length' => $this->getSlugMaxLength(),
            'immutable' => $this->isSlugImmutable(),
        ];
    }

    /* -----------------------------------------------------------------
     |  GENERATION PIPELINE
     |-----------------------------------------------------------------*/

    /**
     * Main generation pipeline
     *
     * @return string
     * @throws RuntimeException
     */
    protected function generate(): string
    {
        // Step 1: Build initial slug
        $slug = $this->buildSlug();

        // Step 2: Normalize (length, trim, etc.)
        $slug = $this->normalize($slug);

        // Step 3: Ensure uniqueness
        $slug = $this->ensureUniqueness($slug);

        return $slug;
    }

    /**
     * Build slug based on configured mode
     *
     * @return string
     * @throws RuntimeException
     */
    protected function buildSlug(): string
    {
        $mode = $this->getSlugMode();

        return match ($mode) {
            'random' => $this->generateRandomSegment(),
            'mixed' => $this->buildMixedSlug(),
            'word' => $this->buildWordSlug(),
            default => throw new RuntimeException("Invalid slug mode: {$mode}"),
        };
    }

    /**
     * Build word-based slug from source
     *
     * @return string
     */
    protected function buildWordSlug(): string
    {
        $source = $this->resolveSourceValue();
        $source = $this->applyTransformations($source);

        return Str::slug($source, $this->getSlugSeparator());
    }

    /**
     * Build mixed slug (words + random)
     *
     * @return string
     */
    protected function buildMixedSlug(): string
    {
        $wordPart = $this->buildWordSlug();
        $randomPart = $this->generateRandomSegment();

        return $wordPart . $this->getSlugSeparator() . $randomPart;
    }

    /**
     * Resolve source value from model
     *
     * @return string
     */
    protected function resolveSourceValue(): string
    {
        $source = $this->getSlugSource();

        if (is_array($source)) {
            return $this->aggregateSourceFields($source);
        }

        return (string) data_get($this, $source, '');
    }

    /**
     * Aggregate multiple source fields
     *
     * @param array $fields
     * @return string
     */
    protected function aggregateSourceFields(array $fields): string
    {
        return collect($fields)
            ->map(fn(string $field) => (string) data_get($this, $field, ''))
            ->filter()
            ->implode(' ');
    }

    /**
     * Apply custom transformations to source
     *
     * @param string $value
     * @return string
     */
    protected function applyTransformations(string $value): string
    {
        $value = trim($value);
        $value = $this->beforeNormalization($value);

        return $value;
    }

    /**
     * Hook for custom source transformation
     *
     * @param string $value
     * @return string
     */
    protected function beforeNormalization(string $value): string
    {
        return $value;
    }

    /* -----------------------------------------------------------------
     |  NORMALIZATION
     |-----------------------------------------------------------------*/

    /**
     * Normalize slug (length, trim, cleanup)
     *
     * @param string $slug
     * @return string
     */
    protected function normalize(string $slug): string
    {
        // Remove leading/trailing separators
        $slug = trim($slug, $this->getSlugSeparator());

        // Apply length limit
        if ($maxLength = $this->getSlugMaxLength()) {
            $slug = $this->truncateToLength($slug, $maxLength);
        }

        // Return fallback if empty after normalization
        return $slug ?: $this->generateFallbackSlug();
    }

    /**
     * Truncate slug to max length safely
     *
     * @param string $slug
     * @param int $maxLength
     * @return string
     */
    protected function truncateToLength(string $slug, int $maxLength): string
    {
        if (strlen($slug) <= $maxLength) {
            return $slug;
        }

        // Truncate and clean up trailing separator
        $truncated = substr($slug, 0, $maxLength);
        return rtrim($truncated, $this->getSlugSeparator());
    }

    /**
     * Generate fallback slug when source is empty
     *
     * @return string
     */
    protected function generateFallbackSlug(): string
    {
        return $this->generateRandomSegment(8);
    }

    /* -----------------------------------------------------------------
     |  UNIQUENESS & COLLISION HANDLING
     |-----------------------------------------------------------------*/

    /**
     * Ensure slug is unique
     *
     * @param string $slug
     * @return string
     */
    protected function ensureUniqueness(string $slug): string
    {
        if (!$this->slugExists($slug)) {
            return $slug;
        }

        return $this->generateUniqueVariant($slug);
    }

    /**
     * Generate unique variant with suffix
     *
     * @param string $baseSlug
     * @return string
     * @throws RuntimeException
     */
    protected function generateUniqueVariant(string $baseSlug): string
    {
        $separator = $this->getSlugSeparator();
        $maxAttempts = 100;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $candidate = "{$baseSlug}{$separator}{$attempt}";

            if (!$this->slugExists($candidate)) {
                return $candidate;
            }
        }

        throw new RuntimeException(
            "Unable to generate unique slug after {$maxAttempts} attempts for " . static::class
        );
    }

    /**
     * Check if slug exists in database
     *
     * @param string $slug
     * @return bool
     */
    protected function slugExists(string $slug): bool
    {
        $query = static::query()->where($this->getSlugColumn(), $slug);

        // Exclude current record if updating
        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        // Apply scoping if defined
        if (method_exists($this, 'scopeSlugUniqueness')) {
            $query = $this->scopeSlugUniqueness($query);
        }

        return $query->exists();
    }

    /**
     * Handle unique constraint violation
     *
     * @return void
     */
    protected function handleUniqueConstraintViolation(): void
    {
        $currentSlug = $this->{$this->getSlugColumn()};
        $newSlug = $this->generateUniqueVariant($currentSlug);

        $this->setSlugAttribute($newSlug);

        Log::warning('Slug collision detected and resolved', [
            'model' => static::class,
            'id' => $this->getKey(),
            'original' => $currentSlug,
            'resolved' => $newSlug,
        ]);
    }

    /* -----------------------------------------------------------------
     |  UPDATE LIFECYCLE
     |-----------------------------------------------------------------*/

    /**
     * Handle slug on model update
     *
     * @return void
     */
    protected function handleSlugOnUpdate(): void
    {
        if ($this->isSlugImmutable()) {
            return;
        }

        if (!$this->shouldRegenerateSlug()) {
            return;
        }

        $this->generateSlugIfNeeded();
    }

    /**
     * Determine if slug should be regenerated on update
     *
     * @return bool
     */
    protected function shouldRegenerateSlug(): bool
    {
        return collect((array) $this->getSlugSource())
            ->contains(fn(string $field) => $this->isDirty($field));
    }

    /* -----------------------------------------------------------------
     |  HELPERS & UTILITIES
     |-----------------------------------------------------------------*/

    /**
     * Generate random string segment
     *
     * @param int $length
     * @return string
     */
    protected function generateRandomSegment(int $length = 12): string
    {
        return Str::lower(Str::random($length));
    }

    /**
     * Check if unique constraint violation
     *
     * @param QueryException $e
     * @return bool
     */
    protected function isUniqueConstraintViolation(QueryException $e): bool
    {
        $message = strtoupper($e->getMessage());

        return str_contains($message, 'UNIQUE')
            || str_contains($message, 'DUPLICATE')
            || str_contains($message, 'CONSTRAINT');
    }

    /**
     * Set slug attribute on model
     *
     * @param string $slug
     * @return void
     */
    protected function setSlugAttribute(string $slug): void
    {
        $this->{$this->getSlugColumn()} = $slug;
    }

    /**
     * Check if slug is locked (immutable)
     *
     * @return bool
     */
    protected function isSlugLocked(): bool
    {
        return $this->isSlugImmutable() && !empty($this->{$this->getSlugColumn()});
    }

    /* -----------------------------------------------------------------
     |  CONFIGURATION
     |-----------------------------------------------------------------*/

    /**
     * Get slug column name
     *
     * @return string
     */
    protected function getSlugColumn(): string
    {
        return property_exists($this, 'slugColumn')
            ? $this->slugColumn
            : 'slug';
    }

    /**
     * Get slug source field(s)
     *
     * @return string|array
     */
    protected function getSlugSource(): string|array
    {
        return property_exists($this, 'slugSource')
            ? $this->slugSource
            : 'title';
    }

    /**
     * Get slug generation mode
     *
     * @return string
     */
    protected function getSlugMode(): string
    {
        return property_exists($this, 'slugMode')
            ? $this->slugMode
            : 'word';
    }

    /**
     * Get slug separator
     *
     * @return string
     */
    protected function getSlugSeparator(): string
    {
        return property_exists($this, 'slugSeparator')
            ? $this->slugSeparator
            : '-';
    }

    /**
     * Get slug maximum length
     *
     * @return int|null
     */
    protected function getSlugMaxLength(): ?int
    {
        if (!property_exists($this, 'slugMaxLength')) {
            return 120;
        }

        return $this->slugMaxLength;
    }

    /**
     * Check if slug is immutable
     *
     * @return bool
     */
    protected function isSlugImmutable(): bool
    {
        return property_exists($this, 'slugImmutable')
            ? $this->slugImmutable
            : false;
    }
}
