<?php

namespace App\Models;

use Filament\Panel;
use App\Enums\Gender;
use App\Enums\Status;
use Morfaw\Supports\EnableSlug;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ngangagah\Relations\UserRelation as RelationsUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use Notifiable;
    use EnableSlug;
    use EnableSlug;
    use RelationsUser;
    use HasRoles;
    use SoftDeletes;

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
        'is_online',
        'slug',
        'last_login_at',
        'last_login_ip',
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

    /**
     * Determine if the user can access a specific Filament panel.
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->hasVerifiedEmail()) {
            return false;
        }
        if (! str_ends_with($this->email, '@gmail.com')) {
            return false;
        }

        return $this->isEmployee();
    }
}
