<?php

namespace Atannex\Relations;

use Exception;
use App\Models\UserActivity;
use App\Models\Comments\Comment;
use App\Models\Controls\Session;
use App\Models\Regions\Employee;
use App\Models\Interactions\Like;
use App\Models\Interactions\View;
use App\Models\Interactions\Share;
use App\Models\Interactions\Rating;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait UserRelation
 *
 * Provides relationship methods for the User model to interact with sessions, employees, comments, and interactions.
 */
trait UserRelation
{
    /**
     * Relationship: User → Sessions
     *
     * A user can have many active/expired sessions.
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Relationship: User → Employee
     *
     * A user may be linked to an employee profile.
     *
     * @return HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Relationship: User → UserActivity
     *
     * Stores the user's last login/logout/seen metadata, including geolocation.
     *
     * @return HasOne
     */
    public function activity(): HasOne
    {
        return $this->hasOne(UserActivity::class, 'user_id');
    }

    /**
     * Check if user has an associated employee profile.
     *
     * Used for role-based access checks and admin restrictions.
     *
     * @return bool
     */
    public function isEmployee(): bool
    {
        try {
            return $this->employee()->exists();
        } catch (Exception $exception) {
            Log::error(sprintf('Error checking employee status for user %s: %s', $this->id, $exception->getMessage()));
            return false;
        }
    }

    /**
     * Relationship: User → Comments
     *
     * A user can post multiple comments.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship: User → Likes
     *
     * Tracks likes a user has given.
     *
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relationship: User → Views
     *
     * Tracks content viewed by the user.
     *
     * @return HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    /**
     * Relationship: User → Shares
     *
     * Tracks shares initiated by the user.
     *
     * @return HasMany
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    /**
     * Relationship: User → Ratings
     *
     * Tracks ratings given by the user.
     *
     * @return HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Clean up expired sessions for the user.
     *
     * Deletes sessions that have expired based on the configured session lifetime.
     *
     * @return int Number of sessions deleted
     */
    public function cleanExpiredSessions(): int
    {
        try {
            $lifetime = config('session.lifetime', 120); // Default: 120 minutes
            return $this->sessions()
                ->where('last_activity', '<', now()->subMinutes($lifetime))
                ->delete();
        } catch (Exception $exception) {
            Log::error(sprintf('Error cleaning expired sessions for user %s: %s', $this->id, $exception->getMessage()));
            return 0;
        }
    }
}
