<?php

namespace Atannex\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait GeneratesSlug
{
    /**
     * Boot the trait.
     */
    public static function bootGeneratesSlug()
    {
        static::creating(function (Model $model) {
            $model->ensureSlug();
        });

        static::updating(function (Model $model) {
            if ($model->shouldRegenerateSlug()) {
                $model->ensureSlug();
            }
        });
    }

    /**
     * Main entry: builds + ensures unique slug.
     */
    public function ensureSlug(): void
    {
        $slug = $this->buildSlug();
        $slug = $this->makeSlugUnique($slug);
        $this->{$this->slugColumn()} = $slug;
    }

    /**
     * Select slug strategy: word / random / mixed.
     */
    protected function buildSlug(): string
    {
        return match ($this->slugMode()) {
            'random' => $this->generateRandomId(),
            'mixed'  => $this->buildWordSlug() . $this->separator() . $this->generateRandomId(),
            default  => $this->buildWordSlug(),
        };
    }

    /**
     * Word-based slug (Str::slug).
     */
    protected function buildWordSlug(): string
    {
        $source = $this->slugSourceValue();
        $source = $this->transformSource($source);
        return Str::slug($source, $this->separator());
    }

    /**
     * Multi-column support.
     */
    protected function slugSourceValue(): string
    {
        $source = $this->slugSource();

        if (is_array($source)) {
            return collect($source)
                ->map(fn($field) => $this->{$field})
                ->filter()
                ->implode(' ');
        }

        return (string) $this->{$source};
    }

    /**
     * Pre-transform hook.
     */
    protected function transformSource(string $value): string
    {
        return trim($value);
    }

    /**
     * Random BBC-style ID (lowercase base62).
     */
    protected function generateRandomId(int $length = 12): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $id = '';

        for ($i = 0; $i < $length; $i++) {
            $id .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return strtolower($id);
    }

    /**
     * Ensure uniqueness.
     */
    protected function makeSlugUnique(string $slug): string
    {
        $base = $slug;
        $count = 1;

        while ($this->slugExists($slug)) {
            $slug = $base . $this->separator() . $count++;
        }

        return $slug;
    }

    /**
     * Check existing slugs.
     */
    protected function slugExists(string $slug): bool
    {
        $query = static::where($this->slugColumn(), $slug);

        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        if (method_exists($this, 'scopeSlugUniqueness')) {
            $query = $this->scopeSlugUniqueness($query);
        }

        return $query->exists();
    }

    /**
     * When to regenerate.
     */
    protected function shouldRegenerateSlug(): bool
    {
        return $this->isDirty($this->slugSource());
    }

    /**
     * Slug mode: word / random / mixed.
     */
    protected function slugMode(): string
    {
        return property_exists($this, 'slugMode')
            ? $this->slugMode
            : 'word';
    }

    /**
     * Slug column.
     */
    protected function slugColumn(): string
    {
        return property_exists($this, 'slugColumn')
            ? $this->slugColumn
            : 'slug';
    }

    /**
     * Slug source column(s).
     */
    protected function slugSource(): string|array
    {
        return property_exists($this, 'slugSource')
            ? $this->slugSource
            : 'title';
    }

    /**
     * Separator.
     */
    protected function separator(): string
    {
        return property_exists($this, 'slugSeparator')
            ? $this->slugSeparator
            : '-';
    }
}
