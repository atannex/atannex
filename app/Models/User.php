<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Controls\Session;
use App\Models\Users\UserActivity;
use App\Enums\Status;
use Atannex\Enables\HasSlug;
use Atannex\Relations\UserRelation;
use Atannex\Traits\HasCleaning;
use Atannex\Traits\HasUserTracking;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use Notifiable;
    use HasSlug;
    use HasRoles;
    use SoftDeletes;
    use HasCleaning;
    use HasUserTracking;
    use UserRelation;

    /**
     * The attribute used as the slug source.
     *
     * Used by EnableSlug trait to generate human-readable slugs.
     *
     * @var string
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

    public function lastActivity(): ?UserActivity
    {
        return $this->activities()->latest()->first();
    }
}
