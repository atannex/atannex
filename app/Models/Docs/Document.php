<?php

namespace App\Models\Docs;

use App\Models\Modules\DocumentModule;
use App\Models\Regions\Employee;
use Atannex\Enables\Scoping;
use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Document extends Model
{
    use Scoping;
    use GeneratesSlug;
    use SoftDeletes;

    protected string $slugMode      = self::MODE_RANDOM;

    protected string $slugColumn    = 'slug';

    protected string|array $slugSource = 'title';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength   = 100;

    protected $fillable = [
        'title',
        'type',
        'slug',
        'slug_path',
        'description',
        'author_id',
        'flag',
        'image',
    ];

    protected $hidden = [
        'author_id',
        'deleted_at',
        'updated_at',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    public function modules(): HasOne
    {
        return $this->hasOne(DocumentModule::class, 'document_id');
    }

    protected static function bootHasSlugPath(): void
    {
        static::creating(function ($model) {
            $model->syncSlugPath();
        });

        static::updating(function ($model) {
            if ($model->isDirty(['slug', 'type'])) {
                $model->syncSlugPath();
            }
        });
    }

    protected function syncSlugPath(): void
    {
        if (! empty($this->type) && ! empty($this->slug)) {
            $this->slug_path = Str::lower(
                trim($this->type, '/') . '/' . trim($this->slug, '/')
            );
        }
    }
}
