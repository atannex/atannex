<?php

namespace App\Models\Pivots;

use App\Models\Regions\Employee;
use App\Models\Regions\Department;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class EmployeeDepartment
 *
 * Pivot model representing the many-to-many relationship between Employees and Departments.
 * Allows tracking which employees belong to which departments.
 *
 * @package App\Models\Pivots
 */
class EmployeeDepartment extends Pivot
{
    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'employee_departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',   // ID of the associated employee
        'department_id', // ID of the associated department
    ];

    /**
     * Get the employee that belongs to this pivot.
     *
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the department that belongs to this pivot.
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
