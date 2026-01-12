<?php

namespace App\Models\Regions;

use App\Enums\Status;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use App\Models\Regions\Employee;
use Atannex\Concerns\DepartmentCode;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivots\EmployeeDepartment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    use DepartmentCode;
    use Scoping;
    use Slugging;
    use SoftDeletes;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'department_code',
        'description',
        'parent_id',
        'slug',
        'phone',
        'email',
        'manager_id',
        'status',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the parent department of the current department.
     *
     * This is useful in cases where departments form a hierarchical structure
     * (e.g., sub-departments under a main department).
     *
     * @return BelongsTo<Department, Department>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    /**
     * Get all child departments under the current department.
     *
     * This defines the inverse of the `parent` relationship, allowing access
     * to immediate sub-departments of a department.
     *
     * @return HasMany<Department>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    /**
     * Get the manager associated with the department.
     *
     * Typically, this references an employee who has been designated
     * as the head or responsible person for this department.
     *
     * @return BelongsTo<Employee, Department>
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Get all employees assigned to the department.
     *
     * This is a many-to-many relationship, since an employee may belong
     * to multiple departments and vice versa. The pivot model
     * `EmployeeDepartment` is used to handle custom pivot logic.
     *
     * @return BelongsToMany<Employee>
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_department')
            ->withTimestamps()
            ->using(EmployeeDepartment::class);
    }
}
