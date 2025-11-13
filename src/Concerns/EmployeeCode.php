<?php

namespace Atannex\Concerns;

use Atannex\Traits\GeneratesUniqueCode;

trait EmployeeCode
{
    use GeneratesUniqueCode;

    public static function bootGeneratesEmployeeCode()
    {
        static::creating(function ($model) {
            $model->generateEmployeeCode();
        });
    }

    public function generateEmployeeCode(): void
    {
        if (! $this->user || empty($this->user->name)) {
            return;
        }

        $abbreviation = $this->generateAbbreviation($this->user->name);

        $this->code = $this->generateUniqueCode($abbreviation);
    }
}
