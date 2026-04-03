<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Status;
use App\Models\Traits\HandleUser;
use Atannex\Foundation\Concerns\GeneratesSlug;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HandleUser;
    use HasRoles;
    use Notifiable;
    use GeneratesSlug;
    use SoftDeletes;

    protected string $slugMode      = self::MODE_WORD;

    protected string $slugColumn    = 'slug';

    protected string|array $slugSource = 'name';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength   = 100;

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
