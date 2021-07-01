<?php

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
        factory(Controle::class, 50)->create();
    }
}
