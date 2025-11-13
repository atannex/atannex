<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelViewed
{
    use Dispatchable;
    use SerializesModels;

    /**
     * The model that was viewed.
     *
     * @var Model
     */
    public $model;

    /**
     * The IP address of the viewer.
     *
     * @var string|null
     */
    public $ip;

    /**
     * Create a new event instance.
     *
     * @param  Model  $model
     */
    public function __construct($model, ?string $ip = null)
    {
        $this->model = $model;
        $this->ip = $ip;
    }
}
