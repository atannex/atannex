<?php

namespace App\Models\Regions;

use App\Enums\Status;
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Illuminate\Database\Eloquent\Model;
use Atannex\Relations\DepartmentRelation;
use Atannex\Traits\GeneratesDepartmentCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use DepartmentRelation;
    use GeneratesDepartmentCode;

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
