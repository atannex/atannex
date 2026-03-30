<?php

namespace App\Models\Regions;

use App\Enums\Status;
use App\Models\Others\SocialMedia;
use App\Models\Pivots\EmployeeDepartment;
use App\Models\Posts\Post;
use App\Models\Posts\Video;
use App\Models\User;
use Atannex\Foundation\Concerns\CanGenerateCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use CanGenerateCode;
    use SoftDeletes;

    protected $codeSourceColumn = 'user.name';

    protected $codePrefix = 'AEMP';

    protected $codeColumn = 'code';

    protected $fillable = [
        'user_id',
        'code',
        'manager_id',
        'status',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

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
        return $this->belongsToMany(Department::class, 'employee_department')
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

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'author_id');
    }

    public function updatedVideos(): HasMany
    {
        return $this->hasMany(Video::class, 'updated_by');
    }
}
