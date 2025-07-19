<?php

namespace App\Models\Docs;

use App\Models\Regions\Employee;
use App\Models\Modules\DocumentModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Morfaw\Supports\EnableScope;
use Morfaw\Supports\EnableSlug;

class Document extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;

    protected string $slugSource = 'title';

    protected $fillable = [
        'title',
        'type',
        'slug',
        'description',
        'author_id',
        'flag',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    public function modules(): HasOne
    {
        return $this->hasOne(DocumentModule::class, 'document_id');
    }
}
