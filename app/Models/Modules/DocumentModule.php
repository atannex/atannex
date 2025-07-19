<?php

namespace App\Models\Modules;

use App\Models\Docs\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentModule extends Model
{
    use SoftDeletes;

    protected $table = 'document_modules';

    protected $fillable = [
        'document_id',
        'content',
        'flag',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
