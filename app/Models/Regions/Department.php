<?php

namespace App\Models\Regions;

use App\Enums\Status;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Relations\DepartmentRelation;
use Atannex\Traits\GeneratesDepartmentCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use DepartmentRelation;
    use GeneratesDepartmentCode;
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
}
