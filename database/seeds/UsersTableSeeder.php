<?php

use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CADATRAR SUPER ADMIN
        User::create([
            'name'              => 'Super',
            'email'             => 'super@super.com',
            'password'          => app('hash')->make('super'),
            'email_verified_at' => Carbon::now()->add(1, 'hour'),
            'created_at'        => Carbon::now()
        ])->roles()->sync([1]);

        // CADATRAR ADMIN
        User::create([
            'name'        => 'Admin',
            'email'       => 'admin@admin.com',
            'password'    => app('hash')->make('admin'),
            'email_verified_at' => Carbon::now()->add(2, 'hour'),
            'created_at'  => Carbon::now()
        ])->roles()->sync([2]);

        // CADATRAR TECNICO
        User::create([
            'name'        => 'Tecnico',
            'email'       => 'tecnico@tecnico.com',
            'password'    => app('hash')->make('tecnico'),
            'email_verified_at' => Carbon::now()->add(2, 'hour'),
            'created_at'  => Carbon::now()
        ])->roles()->sync([3]);

        // CADATRAR 50 USUARIOS TECNICOS
        $faker = Faker::create();

        for ($i = 1; $i <= 50; $i ++)
        {
            $date = Carbon::now()->subDays(rand(1, 28))->subMonth(rand(1, 12));

            User::create([
                'name'     => $faker->name,
                'email'    => $faker->unique()->safeEmail,
                'password' => app('hash')->make('123456'),
                'email_verified_at' => Carbon::parse($date)->addHour(rand(1, 12)),
                'created_at'  => $date,
                'updated_at'  => Carbon::parse($date)->addDay(rand(1, 28))->addHour(rand(1, 12))
            ])->roles()->sync([3]);
        }
    }
}
