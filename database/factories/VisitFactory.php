<?php

declare(strict_types=1);

namespace Canvas\Database\Factories;

use Canvas\Models\Post;
use Canvas\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Visit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id' => Post::factory(),
            'ip' => fake()->ipv4,
            'agent' => fake()->userAgent,
            'referer' => fake()->url,
        ];
    }
}
