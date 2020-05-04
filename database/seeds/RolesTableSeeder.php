<?php

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

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
        ))->perms()->sync([1,5,9,13,14,15,16]);

        Role::create(array(
            'name' => 'usuario',
            'display_name' => 'Usuário',
            'description' => 'Usuário Comum'
        ))->perms()->sync([13,14]);
    }
}
