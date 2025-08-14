<?php

namespace App\Models\Comments\Traits;


trait Scoping
{
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithAllReplies($query)
    {
        return $query->with(['allReplies', 'user']);
    }
}
