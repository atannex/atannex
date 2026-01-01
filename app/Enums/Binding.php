<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Regions\Employee;
use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Binding Enum
 *
 * Represents the binding scope or ownership
 * context for a resource.
 */
final class Binding extends Enum
{
    #[Description('Global')]
    public const GLOBAL = 'global';

    #[Description('Employee')]
    public const EMPLOYEE = Employee::class;
}
