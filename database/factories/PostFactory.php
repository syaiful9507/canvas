<?php

declare(strict_types=1);

namespace Canvas\Database\Factories;

use Canvas\Models\Post;
use Canvas\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => fake()->unique()->uuid,
            'slug' => fake()->unique()->slug,
            'title' => fake()->word,
            'summary' => fake()->sentence,
            'body' => fake()->realText(),
            'published_at' => null,
            'featured_image' => fake()->imageUrl(),
            'featured_image_caption' => fake()->sentence,
            'user_id' => User::factory(),
            'topic_id' => null,
            'meta' => [
                'title' => fake()->sentence,
                'description' => fake()->sentence,
                'canonical_link' => fake()->sentence,
            ],
        ];
    }

    /**
     * Indicate that the model is in draft mode.
     *
     * @return static
     */
    public function draft()
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the model is in published mode.
     *
     * @return static
     */
    public function published()
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => today()->subDay(),
        ]);
    }
}
