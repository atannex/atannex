<?php

declare(strict_types=1);

namespace Atannex\Foundation\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

trait GeneratesSlug
{
    public const MODE_WORD   = 'word';

    public const MODE_RANDOM = 'random';

    public const MODE_MIXED  = 'mixed';

    public const MAX_COLLISION_ATTEMPTS = 100;


    public static function bootGeneratesSlug(): void
    {
        static::saving(fn(Model $model) => $model->generateSlugIfNeeded());

        static::restored(function (Model $model) {
            if (method_exists($model, 'refreshSlugAfterRestore')) {
                $model->refreshSlugAfterRestore();
            }
        });
    }

    public function generateSlugIfNeeded(): void
    {
        if ($this->shouldSkipSlugGeneration()) {
            return;
        }

        $this->setSlug($this->generateSlugPipeline());
    }

    public function regenerateSlug(): void
    {
        $this->setSlug($this->generateSlugPipeline());
    }

    protected function setSlug(string $slug): void
    {
        $this->{$this->getSlugColumn()} = $slug;
    }

    protected function generateSlugPipeline(): string
    {
        $this->validateSlugConfiguration();

        $base = $this->normalize($this->buildSlug());

        return $this->ensureUniqueSlug($base);
    }

    protected function validateSlugConfiguration(): void
    {
        if (!in_array($this->getSlugMode(), [self::MODE_WORD, self::MODE_RANDOM, self::MODE_MIXED], true)) {
            throw new RuntimeException('Invalid slug mode configured.');
        }
    }

    protected function buildSlug(): string
    {
        return match ($this->getSlugMode()) {
            self::MODE_RANDOM => $this->randomSlug(),
            self::MODE_MIXED  => $this->mixedSlug(),
            self::MODE_WORD   => $this->wordSlug(),
            default           => $this->wordSlug(),
        };
    }

    protected function wordSlug(): string
    {
        return Str::slug($this->resolveSource(), $this->getSlugSeparator());
    }

    protected function mixedSlug(): string
    {
        return sprintf(
            '%s%s%s',
            $this->wordSlug(),
            $this->getSlugSeparator(),
            $this->randomSlug(6)
        );
    }

    protected function randomSlug(int $length = 10): string
    {
        return Str::lower(Str::random($length));
    }

    protected function resolveSource(): string
    {
        $source = $this->getSlugSource();

        if (is_array($source)) {
            return collect($source)
                ->map(fn($field) => (string) data_get($this, $field))
                ->filter()
                ->implode(' ');
        }

        return (string) data_get($this, $source);
    }

    protected function normalize(string $slug): string
    {
        $slug = trim($slug, $this->getSlugSeparator());

        if ($max = $this->getSlugMaxLength()) {
            $slug = Str::limit($slug, $max, '');
        }

        return $slug !== '' ? $slug : $this->randomSlug(8);
    }

    protected function ensureUniqueSlug(string $base): string
    {
        if (!$this->slugExists($base)) {
            return $base;
        }

        return $this->resolveCollision($base);
    }

    protected function resolveCollision(string $base): string
    {
        $separator = $this->getSlugSeparator();

        for ($i = 1; $i <= self::MAX_COLLISION_ATTEMPTS; $i++) {
            $candidate = "{$base}{$separator}{$i}";

            if (!$this->slugExists($candidate)) {
                return $candidate;
            }
        }

        return "{$base}{$separator}{$this->randomSlug(6)}";
    }

    protected function slugExists(string $slug): bool
    {
        $query = static::query()->where($this->getSlugColumn(), $slug);

        if (method_exists($this, 'withTrashed')) {
            $query->withTrashed();
        }

        if ($this->exists) {
            $query->whereKeyNot($this->getKey());
        }

        if (method_exists($this, 'applySlugScope')) {
            $query = $this->applySlugScope($query);
        }

        return $query->exists();
    }

    protected function shouldSkipSlugGeneration(): bool
    {
        if ($this->isSlugImmutable() && $this->exists) {
            return true;
        }

        return !$this->hasDirtySlugSource();
    }

    protected function hasDirtySlugSource(): bool
    {
        foreach ($this->getSlugSourceArray() as $field) {
            if ($this->isDirty($field)) {
                return true;
            }
        }

        return false;
    }

    protected function getSlugSourceArray(): array
    {
        return (array) $this->getSlugSource();
    }

    public function saveWithSlugRetry(int $attempts = 3): bool
    {
        return DB::transaction(function () use ($attempts) {
            for ($i = 0; $i < $attempts; $i++) {
                try {
                    return $this->save();
                } catch (QueryException $e) {
                    if (!$this->isUniqueViolation($e)) {
                        throw $e;
                    }

                    $this->regenerateSlug();
                }
            }

            throw new RuntimeException('Exceeded slug retry attempts.');
        });
    }

    protected function refreshSlugAfterRestore(): void
    {
        $this->regenerateSlug();
        $this->saveQuietly();
    }

    protected function isUniqueViolation(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? null;

        return in_array($sqlState, ['23000', '23505'], true)
            || str_contains(strtoupper($e->getMessage()), 'UNIQUE');
    }

    public function getSlugColumn(): string
    {
        return $this->slugColumn ?? 'slug';
    }

    public function getSlugSource(): string|array
    {
        return $this->slugSource ?? 'name';
    }

    public function getSlugMode(): string
    {
        return $this->slugMode ?? self::MODE_WORD;
    }

    public function getSlugSeparator(): string
    {
        return $this->slugSeparator ?? '-';
    }

    public function getSlugMaxLength(): ?int
    {
        return $this->slugMaxLength ?? 120;
    }

    public function isSlugImmutable(): bool
    {
        return $this->slugImmutable ?? false;
    }
}
