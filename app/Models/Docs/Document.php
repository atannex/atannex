<?php

namespace App\Models\Docs;

use App\Models\Modules\DocumentModule;
use App\Models\Regions\Employee;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Document extends Model
{
    use Scoping;
    use Slugging;
    use SoftDeletes;

    /**
     * The attribute used to generate the slug
     */
    protected string $slugSource = 'title';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be hidden for arrays and JSON output.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'author_id',
        'deleted_at',
        'updated_at',
    ];

    /**
     * Get the author (employee) that created the document.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Get the module configuration associated with the document.
     */
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
                trim($this->type, '/').'/'.trim($this->slug, '/')
            );
        }
    }
}
