<?php

use App\Sistema;
use Illuminate\Database\Seeder;

class SistemasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Sistema::class, 20)->create();
    }
}
