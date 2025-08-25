<?php

namespace App\Models\Others;

use Illuminate\Support\Carbon;
use App\Models\User;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Contact
 *
 * Represents a user-submitted contact message or inquiry.
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string $subject
 * @property string $message
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $source
 * @property Status $flag
 * @property Carbon|null $read_at
 * @property Carbon|null $responded_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $user_id
 *
 * @property-read User|null $user
 */
class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'source',
        'read_at',
        'responded_at',
        'flag',
        'user_id',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'responded_at' => 'datetime',
        'flag' => Status::class,
    ];

    /**
     * Get the user who submitted the contact (if any).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to retrieve unread contacts.
     */
    protected function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope to retrieve contacts that are pending response.
     */
    protected function scopePending(Builder $query): Builder
    {
        return $query->where('flag', Status::PENDING);
    }

    /**
     * Mark the contact as read.
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Mark the contact as responded.
     */
    public function markAsResponded(): void
    {
        $this->update(['responded_at' => now()]);
    }

    /**
     * Change the contact's status/flag.
     */
    public function updateFlag(Status $status): void
    {
        $this->update(['flag' => $status]);
    }

    /**
     * Check if the contact has been read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if the contact has been responded to.
     */
    public function isResponded(): bool
    {
        return !is_null($this->responded_at);
    }
}
