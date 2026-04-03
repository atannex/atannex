<?php

declare(strict_types=1);

namespace Atannex\Foundation\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * CanGenerateCode
 *
 * Production-ready unique code generation aligned with GeneratesSlug.
 */
trait CanGenerateCode
{
    /* -----------------------------------------------------------------
     |  LIFECYCLE HOOKS (Aligned with GeneratesSlug)
     |-----------------------------------------------------------------*/

    protected static function bootCanGenerateCode(): void
    {
        static::saving(fn(Model $model) => $model->applyCodeIfNeeded());

        static::restoring(function (Model $model) {
            if (method_exists($model, 'refreshCodeAfterRestore')) {
                $model->refreshCodeAfterRestore();
            }
        });
    }

    /* -----------------------------------------------------------------
     |  PUBLIC API
     |-----------------------------------------------------------------*/

    public function applyCodeIfNeeded(): void
    {
        if ($this->shouldSkipCodeGeneration()) {
            return;
        }

        $this->setCode($this->generateUniqueCode());
    }

    public function regenerateCode(): void
    {
        $this->setCode($this->generateUniqueCode());
    }

    public function getCode(): ?string
    {
        return $this->{$this->getCodeColumn()};
    }

    /* -----------------------------------------------------------------
     |  CONFIGURATION (Public for commands & external use)
     |-----------------------------------------------------------------*/

    public function getCodeColumn(): string
    {
        return $this->codeColumn ?? 'code';
    }

    public function getCodeSource(): string
    {
        return $this->codeSourceColumn ?? 'name';
    }

    public function getCodePrefix(): string
    {
        return $this->codePrefix ?? 'ATA';
    }

    public function getCodeYearFormat(): string
    {
        return $this->codeYearFormat ?? 'y';
    }

    public function getCodeAbbreviationLength(): int
    {
        return max(2, (int) ($this->codeAbbreviationLength ?? 3));
    }

    public function getCodeRandomLength(): int
    {
        return max(4, (int) ($this->codeRandomLength ?? 6));
    }

    public function getMaxCodeAttempts(): int
    {
        return max(5, (int) ($this->codeMaxAttempts ?? 15));
    }

    public function isCodeImmutable(): bool
    {
        return $this->codeImmutable ?? true;
    }

    /**
     * Override in model for custom scoping (tenant, year, etc.)
     */
    public function applyCodeScope(Builder $query): Builder
    {
        return $query;
    }

    /* -----------------------------------------------------------------
     |  CORE GENERATION
     |-----------------------------------------------------------------*/

    protected function generateUniqueCode(): string
    {
        $prefix   = $this->getCodePrefix();
        $year     = now()->format($this->getCodeYearFormat());
        $abbr     = $this->normalizeAbbreviation($this->createAbbreviation($this->getCodeSourceValue()));
        $random   = $this->generateRandomNumericString($this->getCodeRandomLength());
        $maxAttempts = $this->getMaxCodeAttempts();
        $column   = $this->getCodeColumn();

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $candidate = $prefix . $year . $abbr . $random;

            if (!$this->codeExists($candidate)) {
                return $candidate;
            }

            $random = $this->generateRandomNumericString($this->getCodeRandomLength());

            Log::warning('Code collision', [
                'model'     => static::class,
                'candidate' => $candidate,
                'attempt'   => $attempt,
            ]);
        }

        throw new RuntimeException(
            "Failed to generate unique code for " . static::class . " after {$maxAttempts} attempts."
        );
    }

    protected function codeExists(string $code): bool
    {
        $query = $this->applyCodeScope(static::query())
            ->where($this->getCodeColumn(), $code);

        if ($this->exists) {
            $query->whereKeyNot($this->getKey());
        }

        return $query->exists();
    }

    protected function setCode(string $code): void
    {
        $this->{$this->getCodeColumn()} = $code;
    }

    /* -----------------------------------------------------------------
     |  CHANGE DETECTION
     |-----------------------------------------------------------------*/

    protected function shouldSkipCodeGeneration(): bool
    {
        if ($this->isCodeImmutable() && $this->exists) {
            return true;
        }

        return !$this->hasDirtyCodeSource();
    }

    protected function hasDirtyCodeSource(): bool
    {
        $source = $this->getCodeSource();

        if (!str_contains($source, '.')) {
            return $this->isDirty($source);
        }

        return true;
    }

    /* -----------------------------------------------------------------
     |  RESTORE SUPPORT
     |-----------------------------------------------------------------*/

    protected function refreshCodeAfterRestore(): void
    {
        $this->regenerateCode();
        $this->saveQuietly();
    }

    /* -----------------------------------------------------------------
     |  SOURCE & HELPERS
     |-----------------------------------------------------------------*/

    protected function getCodeSourceValue(): string
    {
        $source = $this->getCodeSource();
        return (string) data_get($this, $source, '');
    }

    protected function createAbbreviation(string $text): string
    {
        $text = trim(preg_replace('/[^A-Za-z0-9\s]/', '', $text ?? '') ?? '');

        if ($text === '') {
            return 'XX';
        }

        $words = preg_split('/\s+/', $text) ?: [];
        $abbr = '';
        $target = $this->getCodeAbbreviationLength();

        foreach ($words as $word) {
            if (!empty($word[0])) {
                $abbr .= strtoupper($word[0]);
                if (strlen($abbr) >= $target) break;
            }
        }

        return $abbr ?: 'XX';
    }

    protected function normalizeAbbreviation(string $abbr): string
    {
        $length = $this->getCodeAbbreviationLength();
        return str_pad(strtoupper(substr($abbr, 0, $length)), $length, 'X', STR_PAD_RIGHT);
    }

    protected function generateRandomNumericString(int $length): string
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= random_int(0, 9);
        }

        if ($length > 1 && str_starts_with($result, '0')) {
            $result[0] = (string) random_int(1, 9);
        }

        return $result;
    }
}
