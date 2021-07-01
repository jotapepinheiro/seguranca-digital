<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            /*  1 */ ['name' => 'user-list','display_name' => 'Listar Usuários','description' => 'Visualizar Usuários'],
            /*  2 */ ['name' => 'user-create','display_name' => 'Criar Usuários','description' => 'Criar Novos Usuários'],
            /*  3 */ ['name' => 'user-edit','display_name' => 'Editar Usuários','description' => 'Editar Usuários'],
            /*  4 */ ['name' => 'user-delete','display_name' => 'Deletar Usuários','description' => 'Deletar Usuários'],

            /*  5 */ ['name' => 'role-list','display_name' => 'Listar Funções','description' => 'Visualizar Funções'],
            /*  6 */ ['name' => 'role-create','display_name' => 'Criar Funções','description' => 'Criar Novas Funções'],
            /*  7 */ ['name' => 'role-edit','display_name' => 'Editar Funções','description' => 'Editar Funções'],
            /*  8 */ ['name' => 'role-delete','display_name' => 'Deletar Funções','description' => 'Deletar Funções'],

            /*  9 */ ['name' => 'permission-list','display_name' => 'Listar Permissões','description' => 'Visualizar Permissões'],
            /* 10 */ ['name' => 'permission-create','display_name' => 'Criar Permissões','description' => 'Criar Novas Permissões'],
            /* 11 */ ['name' => 'permission-edit','display_name' => 'Editar Permissões','description' => 'Editar Permissões'],
            /* 12 */ ['name' => 'permission-delete','display_name' => 'Deletar Permissões','description' => 'Deletar Permissões'],

            /* 13 */ ['name' => 'sistema-list','display_name' => 'Listar Sistemas','description' => 'Visualizar Sistemas'],
            /* 14 */ ['name' => 'sistema-create','display_name' => 'Criar Sistemas','description' => 'Criar Novos Sistemas'],
            /* 15 */ ['name' => 'sistema-edit','display_name' => 'Editar Sistemas','description' => 'Editar Sistemas'],
            /* 16 */ ['name' => 'sistema-delete','display_name' => 'Deletar Sistemas','description' => 'Deletar Sistemas'],
       ];

       foreach ($permissions as $key=>$value){
            Permission::create($value);
       }

    }
}
