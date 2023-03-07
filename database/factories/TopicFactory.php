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
            'id' => $this->faker->unique()->uuid,
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->word,
            'featured_image' => $this->faker->imageUrl,
            'user_id' => User::factory()->admin(),
            'meta' => [
                'title' => $this->faker->sentence,
                'description' => $this->faker->sentence,
            ],
        ];
    }
}
