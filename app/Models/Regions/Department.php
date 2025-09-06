<?php

namespace App\Models\Regions;

use App\Enums\Status;
<<<<<<< HEAD
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
=======
use Atannex\Enables\Slug;
use Atannex\Enables\Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
use Illuminate\Database\Eloquent\Model;
use Atannex\Relations\DepartmentRelation;
use Atannex\Traits\GeneratesDepartmentCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
<<<<<<< HEAD
    use EnableSlug;
    use EnableScope;
=======
    use Slug;
    use Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
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
