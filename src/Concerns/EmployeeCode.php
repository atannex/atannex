<?php

declare(strict_types=1);

namespace Atannex\Concerns;

use Atannex\Traits\GeneratesCode;

trait EmployeeCode
{
    use GeneratesCode;

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
