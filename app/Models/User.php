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
 * Optimized User model with efficient relationships and activity tracking.
 *
 * @package App\Models
 */
class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use Notifiable;
    use EnableSlug;
    use HasRoles;
    use SoftDeletes;
    use TracksUserActivity;

    protected string $slugSource = 'name';

    /**
     * The attributes that are mass assignable.
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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
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
     * Determine if the user can access a specific Filament panel.
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
     * Get the sessions associated with the user.
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Get the employee record associated with the user.
     *
     * @return HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get the activity record associated with the user.
     *
     * @return HasOne
     */
    public function activity(): HasOne
    {
        return $this->hasOne(UserActivity::class, 'user_id');
    }

    /**
     * Check if the user is an employee.
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
     * Get the comments associated with the user.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes associated with the user.
     *
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the views associated with the user.
     *
     * @return HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    /**
     * Get the shares associated with the user.
     *
     * @return HasMany
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    /**
     * Get the ratings associated with the user.
     *
     * @return HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
