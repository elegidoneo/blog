<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatePost
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Post
     */
    public $post;

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
     * @param $post
     * @param $before
     * @param $after
     */
    public function __construct($post, $before, $after)
    {
        $this->post = $post;
        $this->before = $before;
        $this->after = $after;
    }
}
