<?php

namespace Database\Factories;

use App\Models\{System, User, Controle};
use Illuminate\Database\Eloquent\Factories\Factory;

class ControleFactory extends Factory
{
    protected $model = Controle::class;

    public function definition(): array
    {
        return [
            'system_id'    => System::all()->random()->id,
            'user_id'       => User::all()->random()->id,
            'justification' => $this->faker->text($maxNbChars = 200)
        ];
    }
}
