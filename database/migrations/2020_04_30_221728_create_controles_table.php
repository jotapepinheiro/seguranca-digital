<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controles', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID de identificação do controle');
            $table->bigInteger('system_id')->comment('ID de referência do sistema')->unsigned()->index();
            $table->bigInteger('user_id')->comment('ID de referência do usuário')->unsigned()->index();

            $table->string('justification', 500)->comment('Justificativa registrada na última para alteração do sistema.');

            $table->foreign('system_id')->references('id')->on('systems')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('controles');
    }
}
