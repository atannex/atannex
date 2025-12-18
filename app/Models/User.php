<?php

namespace App\Models;

use Filament\Panel;
use App\Enums\Gender;
use App\Enums\Status;
use Atannex\Enables\Slugging;
use Atannex\Traits\HasCleaning;
use App\Models\Controls\Session;
use App\Models\Users\UserActivity;
use Atannex\Relations\UserRelation;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Atannex\Concerns\Tracking\TracksAuthenticatedUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasCleaning;
    use HasRoles;
    use TracksAuthenticatedUsers;
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

    public function getImageAttributeName(): string
    {
        return 'image';
    }

    public function getImageDirectory(): string
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

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'user_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(UserActivity::class);
    }
}
