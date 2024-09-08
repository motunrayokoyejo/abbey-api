<?php

namespace Database\Factories;

use App\Enum\Status;
use App\Models\Connection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectionFactory extends Factory
{
    protected $model = Connection::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'follower_id' => User::factory(),
            'status' => $this->faker->randomElement(Status::cases())
        ];
    }
}
