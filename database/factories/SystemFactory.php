<?php

namespace Database\Factories;

use App\Models\{User, System};
use Illuminate\Database\Eloquent\Factories\Factory;

class SystemFactory extends Factory
{
    protected $model = System::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->unique()->company,
            'initial'     => strtoupper(substr($this->faker->unique()->jobTitle, 0, 10)),
            'email'     => $this->faker->unique()->safeEmail,
            'url'       => substr($this->faker->unique()->url, 0, 50),
            'status'    => $this->faker->randomElement(['active','canceled']),
            'created_by' => User::all()->random()->id
        ];
    }
}
