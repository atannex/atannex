<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Regions\Employee;
use Atannex\Filters\GetEnum;
use BenSampo\Enum\Enum;

/**
 * Enum representing media scopes, with UI metadata (label, color, icon).
 *
 * @method static static GLOBAL()
 * @method static static EMPLOYEE()
 */
final class Binding extends Enum
{
    use GetEnum;

    public const EMPLOYEE = Employee::class;

    /**
     * Set UI metadata for each media scope constant.
     */
    public static function boot(): void
    {
        self::setMetadata([
            self::EMPLOYEE => [
                'label' => 'Employee',
                'color' => 'success',
                'icon' => 'heroicon-o-user-circle',
            ],
        ]);
    }
}
