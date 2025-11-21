<?php

namespace Atannex\Concerns;

use Atannex\Traits\GeneratesCode;

trait DepartmentCode
{
    use GeneratesCode;

    public static function bootGeneratesDepartmentCode()
    {
        static::creating(function ($model) {
            $model->generateDepartmentCode();
        });
    }

    public function generateDepartmentCode(): void
    {
        if (empty($this->name)) {
            return;
        }

        $abbreviation = $this->generateAbbreviation($this->name);

        $this->code = $this->generateUniqueCode($abbreviation);
    }
}
