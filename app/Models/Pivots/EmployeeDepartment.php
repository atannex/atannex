<?php

namespace App\Models\Pivots;

use App\Models\Regions\Department;
use App\Models\Regions\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeDepartment extends Pivot
{
    protected $table = 'employee_department';

    protected $fillable = [
        'employee_id',
        'department_id',
    ];

    public function getIncrementing(): bool
    {
        return false;
    }


    public function getKeyName(): string
    {
        return 'employee_id';
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
