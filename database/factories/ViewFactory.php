<?php

declare(strict_types=1);

namespace Canvas\Database\Factories;

use Canvas\Models\Post;
use Canvas\Models\View;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = View::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id' => Post::factory(),
            'ip' => $this->faker->ipv4,
            'agent' => $this->faker->userAgent,
            'referer' => $this->faker->url,
        ];
    }
}
