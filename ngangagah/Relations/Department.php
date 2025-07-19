<?php

namespace Ngangagah\Relations;

use App\Models\Regions\Employee;
use App\Models\Pivots\EmployeeDepartment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Regions\Department as RegionsDepartment;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Department
{
    /**
     * Parent department (if this is a sub-department).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(RegionsDepartment::class, 'parent_id');
    }

    /**
     * Child departments (if this is a parent/umbrella department).
     */
    public function children(): HasMany
    {
        return $this->hasMany(RegionsDepartment::class, 'parent_id');
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
