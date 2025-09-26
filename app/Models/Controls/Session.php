<?php

namespace App\Models\Controls;

use App\Models\User;
use App\Models\Users\UserActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model
{
    protected $table = 'sessions';

    protected $primaryKey = 'id';

    protected $keyType = 'string';


    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'device',
        'platform',
        'browser',
        'last_activity',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(UserActivity::class, 'session_id', 'id');
    }
}
