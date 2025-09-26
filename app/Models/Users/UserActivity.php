<?php

namespace App\Models\Users;

use App\Models\User;
use App\Models\Controls\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    protected $table = 'user_activities';

    protected $fillable = [
        'user_id',
        'session_id',
        'event_type',
        'geo',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'session_id', 'id');
    }
}
