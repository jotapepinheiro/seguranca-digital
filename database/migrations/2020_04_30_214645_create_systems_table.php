<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID de identificação do sistema');
            $table->string('description', 100)->comment('Descrição do sistema para pesquisa');
            $table->string('initial', 10)->comment('Sigla do sistema para pesquisa');
            $table->string('email', 100)->comment('E-mail de atendimento do sistema')->nullable();
            $table->string('url', 50)->comment('URL de acesso ao sistema')->nullable();
            $table->string('status', 50)->comment('Status do Sistema')->default('ativo');

            $table->bigInteger('created_by')->comment('Sistema criado por - UserId')->unsigned()->index();
            $table->bigInteger('updated_by')->comment('Sistema atualizado por - UserId')->nullable()->unsigned()->index();

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('systems');
    }
}
