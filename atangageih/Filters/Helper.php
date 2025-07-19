<?php

namespace Atangageih\Filters;

use App\Enums\Flag;
use App\Enums\Image;
use Illuminate\Support\Str;
use App\Models\Others\Gallery;

/**
 * Trait providing helper methods for common operations.
 *
 * Includes utilities for mapping social media data and default configuration constants.
 */
trait Helper
{

    /**
     * Abbreviate the department name (e.g., "Human Resources" => "HR").
     */
    protected function abbreviateName(string $name): string
    {
        $cleanName = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);

        return collect(explode(' ', Str::headline($cleanName)))
            ->filter()
            ->map(fn($word) => strtoupper(Str::substr($word, 0, 1)))
            ->implode('');
    }

    protected function generateAlphaNumeric(int $length = 4): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        return collect(str_split($characters))
            ->random($length)
            ->implode('');
    }
}
