<?php

namespace App\Models\Interactions;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Share
 *
 * Represents a share interaction on a shareable entity (e.g., post, article) in the application.
 *
 * @package App\Models\Interactions
 */
class Share extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'platform',
        'share_count',
        'shareable_id',
        'shareable_type',
        'shared_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'share_count' => 'integer',
        'shared_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the parent shareable model (e.g., Post, Article).
     */
    public function shareable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who performed the share.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
