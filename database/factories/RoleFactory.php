<?php

namespace Database\Factories;

use App\Models\{Permission, Role};
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $word = $this->faker->unique()->word();

        return [
            'name' => $word,
            'display_name' => strtoupper(substr($word, 0, 10)),
            'description' => $word,
            'permissions' => function () {
                return Permission::select('id')->inRandomOrder()->limit(4)->get()->toArray();
            }
        ];
    }
}
