<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID de identificação do usuário');
            $table->string('name')->comment('Nome do usuário');
            $table->string('email')->comment('E-mail do usuário')->unique();
            $table->string('password')->comment('Senha do usuário');
            $table->timestamp('email_verified_at')->comment('Data de verificação do E-mail')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
