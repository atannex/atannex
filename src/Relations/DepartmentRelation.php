<?php

namespace Atannex\Relations;

use App\Models\Regions\Employee;
use App\Models\Regions\Department;
use App\Models\Pivots\EmployeeDepartment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait DepartmentRelation
{
    /**
     * Parent department (if this is a sub-department).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    /**
     * Child departments (if this is a parent/umbrella department).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_departments')
            ->withTimestamps()
            ->using(EmployeeDepartment::class);
    }
}
