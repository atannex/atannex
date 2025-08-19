<?php

namespace App\Models\Interactions;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Rating
 *
 * Represents a rating interaction on a rateable entity (e.g., post, product) in the application.
 *
 * @package App\Models\Interactions
 */
class Rating extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'rating',
        'rateable_id',
        'rateable_type',
        'rated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'rated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the parent rateable model (e.g., Post, Product).
     *
     * @return MorphTo
     */
    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created the rating.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
