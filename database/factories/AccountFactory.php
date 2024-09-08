<?php

namespace Database\Factories;

use App\Enum\AccountType;
use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'user_id' => User::factory(),
            'account_type' => $this->faker->randomElement(AccountType::cases()),
            'number' => $this->faker->numerify('###########'), // 11-digit random number
            'bank' => $this->faker->randomElement(['GTBank', 'Access Bank', 'UBA', 'Zenith Bank']),
            'balance' => $this->faker->numberBetween(0, 1000000),
            'metadata' => ['notes' => $this->faker->sentence],
        ];
    }
}
