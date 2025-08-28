<?php

namespace Atannex\Traits;

use Illuminate\Support\Str;

trait GeneratesEmployeeCode
{
    public static function bootGeneratesEmployeeCode()
    {
        static::creating(function ($model) {
            $model->generateEmployeeCode();
        });

        // static::updating(function ($model) {
        //     if (empty($model->employee_number)) {
        //         $model->generateEmployeeCode();
        //     }
        // });
    }

    /**
     * Generate employee code like ATA25<EMP_INITIALS><4_DIGIT>
     */
    public function generateEmployeeCode()
    {
        $userName = $this->user?->name;
        if (!$userName) return;

        $year = now()->format('y');

        $abbreviation = collect(explode(' ', preg_replace('/[^A-Za-z0-9 ]/', '', $userName)))
            ->map(fn($word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');

        $abbreviation = Str::substr($abbreviation, 0, 2);

        $tries = 0;
        do {
            $randomNumber = mt_rand(1000, 9999);
            $code = 'ATA' . $year . $abbreviation . $randomNumber;
            $tries++;
        } while (self::where('employee_number', $code)->exists() && $tries < 10);

        $this->employee_number = $code;
    }
}
