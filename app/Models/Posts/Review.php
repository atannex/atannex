<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Flag;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'reviewable_id',
        'reviewable_type',
        'content',
        'reviewer_name',
        'user_id',
        'reviewer_rating',
        'flag',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'reviewer_rating' => 'integer',
        'flag' => Flag::class,
    ];

    /**
     * Polymorphic relationship to the reviewable models.
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The user who submitted the review (optional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
