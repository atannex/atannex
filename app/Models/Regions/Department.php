<?php

namespace App\Models\Regions;

use App\Enums\Status;
use App\Models\Pivots\EmployeeDepartment;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Foundation\Concerns\CanGenerateCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use CanGenerateCode;
    use Scoping;
    use Slugging;
    use SoftDeletes;

    protected string $slugSource = 'name';

    protected $codeSourceColumn = 'name';

    protected $codePrefix = 'ADEP';

    protected $codeColumn = 'code';

    protected $fillable = [
        'name',
        'code',
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

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
        return $this->belongsToMany(Employee::class, 'employee_department')
            ->withTimestamps()
            ->using(EmployeeDepartment::class);
    }
}
