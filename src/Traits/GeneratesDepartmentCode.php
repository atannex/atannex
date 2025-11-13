<?php

namespace Atannex\Traits;

use Illuminate\Support\Str;

trait GeneratesDepartmentCode
{
    public static function bootGeneratesDepartmentCode()
    {
        static::creating(function ($model) {
            $model->generateDepartmentCode();
        });
    }

    /**
     * Generate code like ATA25BA1234
     */
    public function generateDepartmentCode()
    {
        if (! empty($this->name)) {
            // Abbreviation: take first 2 letters of department name (uppercase)
            $abbreviation = collect(explode(' ', preg_replace('/[^A-Za-z0-9 ]/', '', $this->name)))
                ->map(fn ($word) => strtoupper(Str::substr($word, 0, 1)))
                ->implode('');
            $abbreviation = Str::substr($abbreviation, 0, 2); // take first 2 letters

            // Year: last 2 digits
            $year = now()->format('y');

            // Generate unique 4-digit random number
            do {
                $randomNumber = mt_rand(1000, 9999);
                $code = 'ATA'.$year.$abbreviation.$randomNumber;
            } while (self::where('code', $code)->exists());

            $this->code = $code;
        }
    }
}
