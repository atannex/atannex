<?php

namespace App\Models\Regions;

use App\Models\User;
use App\Enums\Status;
use App\Models\Posts\Post;
use App\Models\Others\SocialMedia;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivots\EmployeeDepartment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
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

    /**
     * Get the manager of the employee.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'employee_departments')
            ->withTimestamps()
            ->using(EmployeeDepartment::class);
    }


    public function socialMedia(): MorphMany
    {
        return $this->morphMany(SocialMedia::class, 'owner');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}
