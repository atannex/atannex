<?php

namespace App\Models\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class UserNameToken extends Model
{
    protected $table = 'user_name_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    protected $hidden = [
        'token',
    ];

    /**
     * Get the user that owns the token
     */
    public function user(): BelongsTo
    {
        /** @var User $user */
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the token has expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Generate a unique random token
     *
     * @param  int  $length  The length of the token to generate
     */
    public static function generateToken(int $length = 64): string
    {
        do {
            $token = Str::random($length);
        } while (self::where('token', $token)->exists());

        return $token;
    }
}
