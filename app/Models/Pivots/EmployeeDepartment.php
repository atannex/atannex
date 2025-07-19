<?php

namespace App\Models\Pivots;

use App\Models\Regions\Employee;
use App\Models\Regions\Department;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDepartment extends Pivot
{
    protected $table = 'employee_departments';

    protected $fillable = ['employee_id', 'department_id'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
