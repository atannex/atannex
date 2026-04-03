<?php

namespace App\Models\Posts;

use App\Enums\Flag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reviewable_id',
        'reviewable_type',
        'content',
        'reviewer_name',
        'user_id',
        'reviewer_rating',
        'flag',
        'ip_address',
    ];

    protected $casts = [
        'reviewer_rating' => 'integer',
        'flag' => Flag::class,
    ];

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function canEdit(): bool
    {
        $createdAt = Carbon::parse($this->created_at);

        return $createdAt->diffInMinutes(now()) < 15
            && Flag::allowsEditing($this->flag);
    }
}
