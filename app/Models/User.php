<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Status;
use Atannex\Enables\HasSlug;
use Atannex\Traits\HasCleaning;
use Atannex\Relations\UserRelation;
use Atannex\Traits\TracksUserActivity;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * Represents the system's core authenticated entity.
 *
 * Features:
 * - Implements Laravel authentication & email verification.
 * - Integrates role/permission management via Spatie.
 * - Supports soft deletes for recoverability.
 * - Auto-generates slugs for SEO-friendly URLs.
 * - Tracks user activity (login/logout/last seen) via reusable trait.
 * - Integrates with Filament for admin panel access.
 * - Supports relationships for sessions, comments, likes, views, shares, ratings, and employee profiles.
 */
class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use Notifiable;
    use HasSlug;
    use HasRoles;
    use SoftDeletes;
    use TracksUserActivity;
    use UserRelation;
    use HasCleaning;

    /**
     * Slug source attribute (used by HasSlug).
     */
    public const SLUG_SOURCE = 'name';

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
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
        'timezone',
    ];

    /**
     * Attributes hidden from serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting rules.
     *
     * @return array<string, string>
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

    /**
     * Image attribute used by HasCleaning trait.
     */
    public function getImageAttributeName(): string
    {
        return 'image';
    }

    /**
     * Directory used by HasCleaning trait.
     */
    public function getImageDirectory(): string
    {
        return 'users';
    }

    /**
     * Check if user can access the given Filament admin panel.
     *
     * Business rules:
     * - Requires verified email.
     * - Email domain must be allowed (configurable via config/filament.php).
     * - Must be associated with an active employee record with roles.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail()
            && $this->hasAllowedDomain(
                config('filament.allowed_email_domains', ['gmail.com', 'atannex.org', 'atannex.com'])
            )
            && $this->isEmployee();
    }
}
