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
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Core User entity.
 *
 * Handles authentication, authorization, and Filament access.
 * Domain-specific behavior is delegated to traits.
 */
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HandleUser;
    use HasRoles;
    use Notifiable;
    use Slugging;
    use SoftDeletes;

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

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail()
            && $this->hasAllowedDomain(['gmail.com', 'atannex.org', 'atannex.com'])
            && $this->isEmployee();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
