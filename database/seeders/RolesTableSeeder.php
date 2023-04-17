<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        Role::create(array(
            'name' => 'super',
            'display_name' => 'Super Administrador',
            'description' => 'Super Administrador do Sistema'
        ));

        Role::create(array(
            'name' => 'admin',
            'display_name' => 'Administrador',
            'description' => 'Administrador do Sistema'
        ))->perms()->sync([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]);

        Role::create(array(
            'name' => 'tecnico',
            'display_name' => 'Técnico',
            'description' => 'Usuário Técnico'
        ))->perms()->sync([13,14]);
    }
}
