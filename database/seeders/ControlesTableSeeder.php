<?php

namespace Database\Seeders;

use App\Models\Controle;
use Illuminate\Database\Seeder;

class ControlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Controle::factory()->count(50)->create();
    }
}
