<?php

namespace App\Models\Regions;

use App\Models\User;
use App\Enums\Status;
use App\Models\Posts\Post;
use App\Models\Others\SocialMedia;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivots\EmployeeDepartment;
use Atannex\Traits\GeneratesEmployeeCode;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany, BelongsToMany, HasMany};

class Employee extends Model
{
    use GeneratesEmployeeCode;
    // use SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_number',
        'job_title',
        'hire_date',
        'employment_type',
        'manager_id',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'status' => Status::class,
    ];

    /** Relationships */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'employee_departments')
            ->using(EmployeeDepartment::class)
            ->withTimestamps();
    }

    public function socialMedia(): MorphMany
    {
        return $this->morphMany(SocialMedia::class, 'owner');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /** Scopes */

    protected function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE);
    }

    protected function scopeByDepartment($query, $departmentId)
    {
        return $query->whereHas('departments', fn($q) => $q->where('departments.id', $departmentId));
    }
}
