<?php

namespace Database\Factories;

use App\Helpers\AccountTypes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * @var int
     */
    private static int $order = 1;

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
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => 'user-' . self::$order++ . '@devcorp.com',
            'email_verified_at' => now(),
            'password' => 'password', // Hashing : by default
            'account_type' => $this->faker->randomElement(AccountTypes::getAccountTypeKeys()),
            'remember_token' => Str::random(10),
        ];
    }
}
