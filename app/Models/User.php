<?php

namespace App\Models;

use Filament\Panel;
use App\Enums\Gender;
use App\Enums\Status;
use Atannex\Enables\Slugging;
use Atannex\Traits\HasCleaning;
use Atannex\Relations\UserRelation;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasCleaning;
    use HasRoles;
    use Notifiable;
    use Slugging;
    use SoftDeletes;
    use UserRelation;

    /**
     * The attribute used as the slug source.
     *
     * Used by EnableSlug trait to generate human-readable slugs.
     */
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

       /* -----------------------------------------------------------------
     |  Image Handling (Universal)
     | -----------------------------------------------------------------
     */

    /**
     * Return the image attributes for this model.
     */
    public function images(): array
    {
        return ['image'];
    }

    /**
     * Return the storage directory for images.
     */
    public function dir(): string
    {
        return 'users';
    }

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
}
