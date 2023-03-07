<?php

declare(strict_types=1);

namespace Canvas\Database\Factories;

use Canvas\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->uuid,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->slug, // $this->faker->userName breaks alpha_dash validation
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'summary' => $this->faker->sentence,
            'avatar' => $this->faker->imageUrl,
            'dark_mode' => $this->faker->numberBetween(1, 2),
            'digest' => $this->faker->numberBetween(1, 2),
            'locale' => $this->faker->locale,
            'role' => $this->faker->numberBetween(1, 3),
            'cover_image' => $this->faker->imageUrl,
            'location' => $this->faker->city,
            'website' => $this->faker->url,
            'social' => [
                'twitter' => $this->faker->userName,
                'github' => $this->faker->userName,
            ],
            'meta' => [
                'title' => $this->faker->sentence,
                'description' => $this->faker->sentence,
            ],
        ];
    }

    /**
     * Indicate that the model's role email is a Contributor.
     *
     * @return static
     */
    public function contributor()
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::$contributor_id,
        ]);
    }

    /**
     * Indicate that the model's role email is an Editor.
     *
     * @return static
     */
    public function editor()
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::$editor_id,
        ]);
    }

    /**
     * Indicate that the model's role email is an Admin.
     *
     * @return static
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::$admin_id,
        ]);
    }

    /**
     * Indicate that the model's digest email is enabled.
     *
     * @return static
     */
    public function enabledDigest()
    {
        return $this->state(fn (array $attributes) => [
            'digest' => 1,
        ]);
    }

    /**
     * Indicate that the model's digest email is disabled.
     *
     * @return static
     */
    public function disabledDigest()
    {
        return $this->state(fn (array $attributes) => [
            'digest' => null,
        ]);
    }

    /**
     * Indicate that the model has not specified an avatar.
     *
     * @return static
     */
    public function withoutAvatar()
    {
        return $this->state(fn (array $attributes) => [
            'avatar' => null,
        ]);
    }

    /**
     * Indicate that the model has not specified an locale.
     *
     * @return static
     */
    public function withoutLocale()
    {
        return $this->state(fn (array $attributes) => [
            'locale' => null,
        ]);
    }
}
