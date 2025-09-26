<?php

namespace Atannex\Relations;

use App\Enums\Status;
use App\Models\Comments\Comment;
use App\Models\Interactions\Like;
use App\Models\Interactions\Rating;
use App\Models\Interactions\Share;
use App\Models\Interactions\View;
use App\Models\Regions\Employee;
use App\Models\Users\UserNameToken;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Trait UserRelation
 *
 * Provides relationship methods for the User model to interact with sessions,
 * employees, comments, and interactions.
 */
trait UserRelation
{
    public function userNameToken(): HasOne
    {
        return $this->hasOne(UserNameToken::class);
    }

    /**
     * Relationship: User → Employee
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get the email domain.
     */
    public function emailDomain(): string
    {
        return explode('@', $this->email)[1];
    }

    /**
     * Check if the user's email domain is in the allowed list.
     */
    public function hasAllowedDomain(array $allowedDomains): bool
    {
        return in_array($this->emailDomain(), $allowedDomains, true);
    }

    /**
     * Relationship: User → Active Employee
     */
    public function activeEmployee(): HasOne
    {
        return $this->hasOne(Employee::class)->where('status', Status::ACTIVE);
    }

    /**
     * Check if user has an associated active employee profile with roles.
     */
    public function isEmployee(): bool
    {
        return $this->hasVerifiedEmail()
            && $this->activeEmployee()->exists()
            && $this->roles()->exists();
    }

    /**
     * Relationship: User → Comments
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship: User → Likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relationship: User → Views
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    /**
     * Relationship: User → Shares
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    /**
     * Relationship: User → Ratings
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
