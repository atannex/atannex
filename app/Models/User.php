<?php

namespace App\Models;

use Filament\Panel;
use App\Enums\Gender;
use App\Enums\Status;
use Atannex\Enables\EnableSlug;
use App\Models\Comments\Comment;
use App\Models\Controls\Session;
use App\Models\Regions\Employee;
use App\Models\Interactions\Like;
use App\Models\Interactions\View;
use App\Models\Interactions\Share;
use App\Models\Interactions\Rating;
use Atannex\Traits\TracksUserActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;

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
 */
class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use Notifiable;
    use EnableSlug;
    use HasRoles;
    use SoftDeletes;
    use TracksUserActivity;

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
     * - Restricts access to Gmail addresses (example business rule).
     * - Must be associated with an Employee record.
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        try {
            if (!$this->hasVerifiedEmail() || !str_ends_with($this->email, '@gmail.com')) {
                return false;
            }

            return $this->isEmployee();
        } catch (\Exception $e) {
            Log::error("Error checking panel access for user {$this->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Relationship: User → Sessions
     *
     * A user can have many active/expired sessions.
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Relationship: User → Employee
     *
     * A user may be linked to an employee profile.
     *
     * @return HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Relationship: User → UserActivity
     *
     * Stores the user's last login/logout/seen metadata.
     *
     * @return HasOne
     */
    public function activity(): HasOne
    {
        return $this->hasOne(UserActivity::class, 'user_id');
    }

    /**
     * Check if user has an associated employee profile.
     *
     * Used for role-based access checks and admin restrictions.
     *
     * @return bool
     */
    public function isEmployee(): bool
    {
        try {
            return $this->employee()->exists();
        } catch (\Exception $e) {
            Log::error("Error checking employee status for user {$this->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Relationship: User → Comments
     *
     * A user can post multiple comments.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship: User → Likes
     *
     * Tracks likes a user has given.
     *
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relationship: User → Views
     *
     * Tracks content viewed by the user.
     *
     * @return HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    /**
     * Relationship: User → Shares
     *
     * Tracks shares initiated by the user.
     *
     * @return HasMany
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    /**
     * Relationship: User → Ratings
     *
     * Tracks ratings given by the user.
     *
     * @return HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
