<?php

namespace Atannex\Traits;

use Illuminate\Support\Str;

trait GeneratesUniqueCode
{
    /**
     * Generate the base code: ATA + year + abbreviation + unique 4-digit number
     */
    public function generateUniqueCode(string $abbreviation, string $field = 'code'): string
    {
        $year = now()->format('y');
        $abbreviation = Str::upper(Str::substr($abbreviation, 0, 2));

        $tries = 0;
        do {
            $randomNumber = mt_rand(1000, 9999);
            $code = 'ATA' . $year . $abbreviation . $randomNumber;

            $exists = self::where($field, $code)->exists();
            $tries++;
        } while ($exists && $tries < 10);

        return $code;
    }

    /**
     * Extract abbreviation from a given string (name, department, etc.)
     */
    protected function generateAbbreviation(string $text, int $max = 2): string
    {
        $clean = preg_replace('/[^A-Za-z0-9 ]/', '', $text);

        return collect(explode(' ', $clean))
            ->map(fn($w) => Str::substr($w, 0, 1))
            ->implode('');
    }
}
