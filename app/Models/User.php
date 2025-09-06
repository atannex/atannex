<?php

namespace App\Models;

use Exception;
use Filament\Panel;
use App\Enums\Gender;
use App\Enums\Status;
<<<<<<< HEAD
use Atannex\Enables\EnableSlug;
=======
use Atannex\Enables\Slug;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
use Atannex\Relations\UserRelation;
use Illuminate\Support\Facades\Log;
use Atannex\Traits\TracksUserActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
<<<<<<< HEAD
    use EnableSlug;
=======
    use Slug;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
    use HasRoles;
    use SoftDeletes;
    use TracksUserActivity;
    use UserRelation;

    /**
     * The attribute used as the slug source.
     *
     * Used by EnableSlug trait to generate human-readable slugs.
     *
     * @var string
     */
    protected string $slugSource = 'name';

    /**
     * Mass assignable attributes.
     *
     * Restricts which fields can be set via `create()` or `update()`.
     * Helps prevent mass assignment vulnerabilities.
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
    ];

    /**
     * Attributes hidden from serialization.
     *
     * Ensures sensitive data (e.g., password, tokens) is never exposed in API responses.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes to native or custom types.
     *
     * - email_verified_at → Carbon datetime
     * - password → Laravel's auto-hash
     * - date_of_birth → Carbon date
     * - gender/status → Backed Enums (App\Enums)
     *
     * @return array<string, string>
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
     * Check if user can access the given Filament admin panel.
     *
     * Business logic:
     * - Requires verified email.
     * - Restricts access to allowed email domains (configurable via config/filament.php).
     * - Must be associated with an Employee record.
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        try {
            if (!$this->hasVerifiedEmail()) {
                return false;
            }

            $allowedDomains = config('filament.allowed_email_domains', ['gmail.com', 'atannex.org', 'atannex.com']);
            $emailDomain = explode('@', $this->email)[1] ?? '';
            if (!in_array($emailDomain, $allowedDomains, true)) {
                return false;
            }

            return $this->isEmployee();
        } catch (Exception $exception) {
            Log::error(sprintf('Error checking panel access for user %s: %s', $this->id, $exception->getMessage()));
            return false;
        }
    }
}
