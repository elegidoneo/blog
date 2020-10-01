<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    /**
     * @var array
     */
    public $before;

    /**
     * @var array
     */
    public $after;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param array $before
     * @param array $after
     */
    public function __construct($user, array $before, array $after)
    {
        $this->user = $user;
        $this->before = $before;
        $this->after = $after;
    }
}
