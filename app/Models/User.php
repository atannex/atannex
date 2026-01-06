<?php

namespace App\Models;

use Filament\Panel;
use App\Enums\Gender;
use App\Enums\Status;
use Atannex\Enables\Slugging;
use App\Models\Comments\Comment;
use App\Models\Regions\Employee;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Atannex\Contracts\HasImages;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasImages
{
    use HasRoles, Notifiable, Slugging, SoftDeletes;

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
            'metadata' => 'array', // cast JSON to array
        ];
    }

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