<?php

declare(strict_types=1);

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Canvas\Models\User::class, function (Faker\Generator $faker) {
    return [
        'id' => $this->faker->uuid,
        'name' => $this->faker->name,
        'email' => $this->faker->safeEmail,
        'username' => Str::slug($this->faker->userName),
        'password' => bcrypt($this->faker->password),
        'summary' => $this->faker->sentence,
        'avatar' => md5(trim(Str::lower($this->faker->email))),
        'dark_mode' => $this->faker->numberBetween(0, 1),
        'digest' => $this->faker->numberBetween(0, 1),
        'locale' => $this->faker->locale,
        'role' => $this->faker->numberBetween(1, 3),
    ];
});
