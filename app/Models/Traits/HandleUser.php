<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Enums\Status;
use App\Models\Comments\Comment;
use App\Models\Regions\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Encapsulates reusable user-related behavior.
 *
 * Assumes all data is valid and consistently available.
 */
trait HandleUser
{
    /**
     * User-authored comments.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Extract the domain portion of the user's email.
     *
     * Email format integrity is guaranteed.
     */
    public function emailDomain(): string
    {
        return substr(strrchr($this->email, '@'), 1);
    }

    /**
     * Determine whether the user's email domain is permitted.
     */
    public function hasAllowedDomain(array $allowedDomains): bool
    {
        return in_array($this->emailDomain(), $allowedDomains, true);
    }

    /**
     * Primary employee relationship.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Active employee relationship.
     *
     * Used to enforce access and employment status.
     */
    public function activeEmployee(): HasOne
    {
        return $this->employee()
            ->where('status', Status::ACTIVE);
    }

    /**
     * Determine whether the user qualifies as an employee.
     *
     * Requires an active employee record and assigned roles.
     */
    public function isEmployee(): bool
    {
        return $this->activeEmployee()->exists()
            && $this->roles()->exists();
    }
}
