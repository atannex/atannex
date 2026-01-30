<?php

namespace App\Models\Comments;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rateable extends Model
{
    use SoftDeletes;

    protected $table = 'rateables';

    protected $fillable = [
        'user_id',
        'rating',
        'comment',
        'ip_address',
        'rating_hash',
    ];

    /**
     * Polymorphic relation to any rateable model.
     */
    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relation to the user who submitted the rating.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique hash for a rating to prevent duplicates.
     */
    public static function generateRatingHash($userId, $rateableType, $rateableId): string
    {
        return hash('sha256', $userId . '|' . $rateableType . '|' . $rateableId);
    }

    /**
     * Scope to filter ratings by a specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter ratings for a specific rateable model.
     */
    public function scopeForModel($query, $modelType, $modelId)
    {
        return $query->where('rateable_type', $modelType)
                     ->where('rateable_id', $modelId);
    }

    /**
     * Scope to filter ratings greater than or equal to a value.
     */
    public function scopeMinRating($query, int $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Scope to filter ratings less than or equal to a value.
     */
    public function scopeMaxRating($query, int $rating)
    {
        return $query->where('rating', '<=', $rating);
    }

    /**
     * Calculate average rating for a given model.
     */
    public static function averageRating($modelType, $modelId): float
    {
        return (float) self::forModel($modelType, $modelId)->avg('rating');
    }

    /**
     * Check if a user has already rated a model.
     */
    public static function hasUserRated($userId, $modelType, $modelId): bool
    {
        $hash = self::generateRatingHash($userId, $modelType, $modelId);
        return self::where('rating_hash', $hash)->exists();
    }
}
