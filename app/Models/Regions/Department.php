<?php

namespace App\Models\Regions;

use App\Enums\Status;
use Atannex\Enables\HasSlug;
use Atannex\Enables\HasScope;
use Illuminate\Database\Eloquent\Model;
use Atannex\Relations\DepartmentRelation;
use Atannex\Traits\GeneratesDepartmentCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasScope;
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
