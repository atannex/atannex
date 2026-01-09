<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Status;
use App\Models\Traits\HandleUser;
use Atannex\Enables\Slugging;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

/**
 * Core User entity.
 *
 * Handles authentication, authorization, and Filament access.
 * Domain-specific behavior is delegated to traits.
 */
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasRoles;
    use Slugging;
    use SoftDeletes;
    use Notifiable;
    use HandleUser;

    /**
     * Attribute used as the source for slug generation.
     */
    protected string $slugSource = 'name';

    /**
     * Mass assignable attributes.
     *
     * Validation and data integrity are enforced upstream.
     */
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
        'locale',
        'timezone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'bio',
        'metadata',
    ];

    /**
     * Attributes excluded from serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
<<<<<<< HEAD
     * Define the model's attribute cast mappings.
     *
     * @return array Associative array mapping attribute names to cast types or enum/class names (e.g., `'email_verified_at' => 'datetime'`).
=======
     * Attribute casting rules.
     *
     * Enums are treated as authoritative domain values.
>>>>>>> development
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'date_of_birth'     => 'date',
            'gender'            => Gender::class,
            'status'            => Status::class,
        ];
    }

<<<<<<< HEAD
    /* -----------------------------------------------------------------
     |  Image Handling
     | -----------------------------------------------------------------
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
=======
    /**
     * Determine whether the user may access a Filament panel.
     *
     * Access is granted only to verified users with
     * an allowed email domain and an active employee record.
>>>>>>> development
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail()
            && $this->hasAllowedDomain(
                config('filament.allowed_email_domains', ['gmail.com', 'atannex.org', 'atannex.com'])
            )
            && $this->isEmployee();
    }
<<<<<<< HEAD

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
    public function allowedStatusTransitions(): array
    {
        return Status::allowedTransitions($this->status);
    }

    public function canTransitionTo(string $targetStatus): bool
    {
        return Status::canTransition($this->status, $targetStatus);
    }

    public function updateStatus(string $newStatus): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            return false;
        }

        $this->status = $newStatus;
        return $this->save();
    }

    public function isActive(): bool
    {
        return $this->status === Status::ACTIVE;
    }

    public function isRestricted(): bool
    {
        return $this->status === Status::RESTRICTED;
    }

    public function isSuspended(): bool
    {
        return $this->status === Status::SUSPENDED;
    }

    public function isBanned(): bool
    {
        return $this->status === Status::BANNED;
    }

    public function isPending(): bool
    {
        return $this->status === Status::PENDING;
    }

    public function isUnverified(): bool
    {
        return $this->status === Status::UNVERIFIED;
    }
}
=======
}
>>>>>>> development
