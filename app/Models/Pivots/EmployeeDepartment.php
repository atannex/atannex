<?php

namespace App\Models\Pivots;

use App\Models\Regions\Department;
use App\Models\Regions\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class EmployeeDepartment
 *
 * Pivot model representing the many-to-many relationship between Employees and Departments.
 * Allows tracking which employees belong to which departments.
 */
class EmployeeDepartment extends Pivot
{
    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'employee_department';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'department_id',
    ];

    /**
     * Get the employee that belongs to this pivot.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the department that belongs to this pivot.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
