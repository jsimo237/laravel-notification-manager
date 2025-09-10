<?php

namespace Kirago\NotificationManager\Tests\TestSupport\Factories;

use function bcrypt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function now;
use Kirago\NotificationManager\Tests\TestSupport\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
