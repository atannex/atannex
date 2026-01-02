<?php

namespace App\Models;

use Filament\Panel;
use App\Enums\Gender;
use App\Enums\Status;
use Atannex\Enables\Slugging;
use Atannex\Traits\HasCleaning;
use App\Models\Comments\Comment;
use App\Models\Regions\Employee;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasCleaning;
    use HasRoles;
    use Notifiable;
    use Slugging;
    use SoftDeletes;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'date_of_birth',
        'gender',
        'phone',
        'status',
        'slug',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Define the model's attribute cast mappings.
     *
     * @return array Associative array mapping attribute names to cast types or enum/class names (e.g., `'email_verified_at' => 'datetime'`).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'gender' => Gender::class,
            'status' => Status::class,
        ];
    }

    /**
     * List image field names used by the model.
     *
     * @return string[] Array of image attribute keys.
     */
    public function images(): array
    {
        return ['image'];
    }

    /**
     * Get the storage directory name used for user files.
     *
     * @return string The storage directory for users (e.g., "users").
     */
    public function dir(): string
    {
        return 'users';
    }

    /**
     * Determine whether the user is allowed to access the given Filament admin panel.
     *
     * Access is granted only when the user's email is verified, their email domain is in the allowed
     * domains list, and the user is associated with an employee record.
     *
     * @param Panel $panel The Filament panel being accessed.
     * @return bool `true` if access is permitted, `false` otherwise.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail()
            && $this->hasAllowedDomain(
                config('filament.allowed_email_domains', ['gmail.com', 'atannex.org', 'atannex.com'])
            )
            && $this->isEmployee();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    /**
     * Sends the password reset notification to the user.
     *
     * @param string $token The password reset token included in the notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the user's associated Employee record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne HasOne relationship to the Employee model.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Defines a one-to-one relationship to the user's active Employee record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne The relation scoped to the Employee model where `status` equals `Status::ACTIVE`.
     */
    public function activeEmployee(): HasOne
    {
        return $this->hasOne(Employee::class)->where('status', Status::ACTIVE);
    }

    /**
     * Get the user's comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany A relationship returning Comment models associated with the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the domain part of the user's email address.
     *
     * @return string The substring after the '@' character in the user's email.
     */
    public function emailDomain(): string
    {
        return explode('@', $this->email)[1];
    }

    /**
     * Checks whether the user's email domain is within a set of allowed domains.
     *
     * @param array $allowedDomains List of allowed email domain strings (e.g., `['example.com', 'gmail.com']`).
     * @return bool `true` if the user's email domain is in `$allowedDomains`, `false` otherwise.
     */
    public function hasAllowedDomain(array $allowedDomains): bool
    {
        return in_array($this->emailDomain(), $allowedDomains, true);
    }

    /**
     * Determine whether the user is considered an employee.
     *
     * @return bool `true` if the user has a verified email, an active employee record, and at least one role; `false` otherwise.
     */
    public function isEmployee(): bool
    {
        return $this->hasVerifiedEmail()
            && $this->activeEmployee()->exists()
            && $this->roles()->exists();
    }

    /* -----------------------------------------------------------------
     |  Status Lifecycle Helpers
     | -----------------------------------------------------------------
     */

    /**
     * Determine which status values the user may transition to from their current status.
     *
     * @return string[] Array of status identifiers the user can transition to.
     */
    public function allowedStatusTransitions(): array
    {
        return Status::allowedTransitions($this->status);
    }

    /**
         * Determine whether the user may transition from their current status to the given target status.
         *
         * @param string $targetStatus The desired status to transition to.
         * @return bool `true` if the transition from the user's current status to `$targetStatus` is allowed, `false` otherwise.
         */
    public function canTransitionTo(string $targetStatus): bool
    {
        return Status::canTransition($this->status, $targetStatus);
    }

    /**
     * Update the user's status when the transition to the given status is permitted.
     *
     * @param string $newStatus The target status to transition the user to.
     * @return bool `true` if the status was changed and the model was saved, `false` if the transition is not allowed or saving failed.
     */
    public function updateStatus(string $newStatus): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            return false;
        }

        $this->status = $newStatus;
        return $this->save();
    }

    /**
     * Determine whether the user's status is active.
     *
     * @return bool `true` if the user's status equals `Status::ACTIVE`, `false` otherwise.
     */
    public function isActive(): bool
    {
        return $this->status === Status::ACTIVE;
    }

    /**
     * Determine whether the user has status RESTRICTED.
     *
     * @return bool `true` if the user's status is `Status::RESTRICTED`, `false` otherwise.
     */
    public function isRestricted(): bool
    {
        return $this->status === Status::RESTRICTED;
    }

    /**
     * Determine whether the user is suspended.
     *
     * @return bool `true` if the user's status is `Status::SUSPENDED`, `false` otherwise.
     */
    public function isSuspended(): bool
    {
        return $this->status === Status::SUSPENDED;
    }

    /**
     * Determine whether the user's status is banned.
     *
     * @return bool `true` if the user's status equals `Status::BANNED`, `false` otherwise.
     */
    public function isBanned(): bool
    {
        return $this->status === Status::BANNED;
    }

    /**
     * Determines whether the user's status is pending approval.
     *
     * @return bool `true` if the user's status equals `Status::PENDING`, `false` otherwise.
     */
    public function isPending(): bool
    {
        return $this->status === Status::PENDING;
    }

    /**
     * Determine whether the user's status is UNVERIFIED.
     *
     * @return bool `true` if the user's status is Status::UNVERIFIED, `false` otherwise.
     */
    public function isUnverified(): bool
    {
        return $this->status === Status::UNVERIFIED;
    }
}