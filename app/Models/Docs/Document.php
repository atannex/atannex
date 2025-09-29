<?php

namespace App\Models\Docs;

use Atannex\Enables\HasSlug;
use Atannex\Enables\HasScope;
use App\Models\Regions\Employee;
use App\Models\Modules\DocumentModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasScope;

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
        'description',
        'author_id',
        'flag',
        'image'
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
}
