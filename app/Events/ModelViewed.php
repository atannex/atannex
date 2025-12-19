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

    public Model $model;
    public string $visitorId;
    public ?int $userId;
    public ?string $ip;
    public ?string $userAgent;

    public function __construct(Model $model)
    {
        $this->model     = $model;
        $this->visitorId = app('visitor_id');
        $this->userId    = Auth::id();
        $this->ip        = Request::ip();
        $this->userAgent = Request::userAgent();
    }
}
