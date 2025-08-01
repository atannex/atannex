<?php

namespace App\Models\Regions;

use App\Enums\Status;
use Morfaw\Supports\EnableSlug;
use Morfaw\Supports\EnableScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ngangagah\Relations\DepartmentRelation;

class Department extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use DepartmentRelation;

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
}
