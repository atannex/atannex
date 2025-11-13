<?php

namespace App\Models\Modules;

use App\Models\Docs\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentModule extends Model
{
    use SoftDeletes;

    /**
     * The associated table.
     *
     * @var string
     */
    protected $table = 'document_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_id',
        'content',
        'flag',
    ];

    /**
     * Get the document that owns this module.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
