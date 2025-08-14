<?php

namespace Ngangagah\Relations;

use App\Models\Others\Contact;
use App\Models\Controls\Session;
use App\Models\Regions\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Check if the user has an employee record.
     */
    public function isEmployee(): bool
    {
        return $this->employee()->exists();
    }
}
