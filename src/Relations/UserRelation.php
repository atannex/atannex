<?php

namespace Atannex\Relations;

use App\Enums\Status;
use App\Models\Comments\Comment;
use App\Models\Regions\Employee;
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
}
