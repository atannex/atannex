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

    public function dir(): string
    {
        return 'users';
    }

    /* -----------------------------------------------------------------
     |  Filament Admin Access
     | -----------------------------------------------------------------
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function activeEmployee(): HasOne
    {
        return $this->hasOne(Employee::class)->where('status', Status::ACTIVE);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /* -----------------------------------------------------------------
     |  Helper Methods
     | -----------------------------------------------------------------
     */
    public function emailDomain(): string
    {
        return explode('@', $this->email)[1];
    }

    public function hasAllowedDomain(array $allowedDomains): bool
    {
        return in_array($this->emailDomain(), $allowedDomains, true);
    }

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
