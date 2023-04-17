<?php

namespace Database\Factories;

use App\Models\{Permission};
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        $word = $this->faker->unique()->word();

        return [
            'name' => $word,
            'display_name' => strtoupper(substr($word, 0, 10)),
            'description' => $word,
        ];
    }
}
