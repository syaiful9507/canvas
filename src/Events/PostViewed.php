<?php

declare(strict_types=1);

namespace Canvas\Events;

use Canvas\Models\Post;

class PostViewed
{
    /**
     * The post instance.
     *
     * @var \Canvas\Models\Post
     */
    public $post;

    /**
     * Create a new event instance.
     *
     * @param  \Canvas\Models\Post  $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
