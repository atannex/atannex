<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ModelViewed
{
    use Dispatchable, SerializesModels;

    /**
     * The viewed model.
     */
    public Model $model;

    /**
     * Authenticated user ID (null for guests).
     */
    public ?int $userId;

    /**
     * Viewer IP address.
     */
    public ?string $ip;

    /**
     * Viewer user agent.
     */
    public ?string $userAgent;

    /**
     * Create a new event instance.
     */
    public function __construct(Model $model)
    {
        $this->model     = $model;
        $this->userId    = Auth::id();
        $this->ip        = Request::ip();
        $this->userAgent = Request::userAgent();
    }
}
