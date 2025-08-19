<?php

namespace Ngangagah\Relations;

use App\Models\Comments\Comment;
use App\Models\Controls\Session;
use App\Models\Interactions\Like;
use App\Models\Interactions\Rating;
use App\Models\Interactions\Share;
use App\Models\Interactions\View;
use App\Models\Regions\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Trait UserRelation
 *
 * Provides relationship methods for the User model to interact with sessions, employees, comments, and interactions.
 *
 * @package Ngangagah\Relations
 */
trait UserRelation
{
    /**
     * Get the sessions associated with the user.
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Get the employee profile associated with the user.
     *
     * @return HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Determine if the user has an associated employee profile.
     *
     * @return bool
     */
    public function isEmployee(): bool
    {
        return $this->employee()->exists();
    }

    /**
     * Get the comments made by the user.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes made by the user.
     *
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the views recorded for the user.
     *
     * @return HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    /**
     * Get the shares made by the user.
     *
     * @return HasMany
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    /**
     * Get the ratings given by the user.
     *
     * @return HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
