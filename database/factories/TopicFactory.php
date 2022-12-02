<?php

declare(strict_types=1);

namespace Canvas\Database\Factories;

use Canvas\Models\Topic;
use Canvas\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;

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
            'name' => fake()->word,
            'user_id' => User::factory()->admin(),
        ];
    }
}
