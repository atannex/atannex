<?php

namespace Ngangagah\Relations;

use App\Models\Others\Contact;
use App\Models\Controls\Session;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelation
{
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
