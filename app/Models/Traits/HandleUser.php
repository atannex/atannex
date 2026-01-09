<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Enums\Status;
use App\Models\Comments\Comment;
use App\Models\Regions\Employee;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
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
     * Send the custom email verification notification.
     *
     * Overrides Laravel's default implementation.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    /**
     * Send the custom password reset notification.
     *
     * Method signature must remain compatible with
     * Illuminate\Foundation\Auth\User.
     */
     /** @var string $token */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
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
     * Determine whether the user qualifies as an employee.
     *
     * Requires an active employee record and assigned roles.
     */
    public function isEmployee(): bool
    {
        return $this->activeEmployee()->exists()
            && $this->roles()->exists();
    }

    /**
     * Retrieve allowed status transitions for the user.
     */
    public function allowedStatusTransitions(): array
    {
        return Status::allowedTransitions($this->status);
    }

    /**
     * Determine whether a transition to the given status is allowed.
     */
    public function canTransitionTo(Status|string $targetStatus): bool
    {
        return Status::canTransition($this->status, $targetStatus);
    }

    /**
     * Update the user's status.
     *
     * Transition validity is assumed to be enforced externally.
     */
    public function updateStatus(Status|string $newStatus): bool
    {
        $this->status = $newStatus;

        return $this->save();
    }
}
