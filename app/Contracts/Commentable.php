<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Interface Commentable
 *
 * Defines the contract for any model that can have comments.
 * Implementing this interface ensures that the model provides a way to retrieve its comments.
 */
interface Commentable
{
    /**
     * Get all comments associated with this model.
     *
     * @return MorphMany A polymorphic one-to-many relationship to the Comment model.
     */
    public function comments(): MorphMany;
}
