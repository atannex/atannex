<?php

namespace App\Models\Regions;

use App\Models\User;
use App\Enums\Status;
use App\Models\Posts\Post;
use App\Models\Others\SocialMedia;
use App\Models\Pivots\EmployeeDepartment;
use Atannex\Traits\GeneratesEmployeeCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    MorphMany,
    BelongsToMany,
    HasMany
};

class Employee extends Model
{
    use GeneratesEmployeeCode;

    protected $fillable = [
        'user_id',
        'code',
        'manager_id',
        'status',
    ];

    protected $casts = [
        'status'    => Status::class,
    ];

    /** --------------------------------
     * Relationships
     * -------------------------------- */

    /** @return BelongsTo<User, Employee> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Employee, Employee> */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /** @return HasMany<Employee> */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    /** @return BelongsToMany<Department> */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'employee_department')
            ->using(EmployeeDepartment::class)
            ->withTimestamps();
    }

    /** @return MorphMany<SocialMedia> */
    public function socialMedia(): MorphMany
    {
        return $this->morphMany(SocialMedia::class, 'owner');
    }

    /** @return HasMany<Post> */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /** --------------------------------
     * Scopes
     * -------------------------------- */

    protected function scopeActive(Builder $query): Builder
    {
        return $query->where('status', Status::ACTIVE);
    }

    protected function scopeByDepartment(Builder $query, int $departmentId): Builder
    {
        return $query->whereHas('departments', fn($q) => $q->where('departments.id', $departmentId));
    }

    protected function scopeManagers(Builder $query): Builder
    {
        return $query->whereNull('manager_id');
    }
}
