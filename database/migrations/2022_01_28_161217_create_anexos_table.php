<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnexosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anexos', function (Blueprint $table) {
            $table->id();

            $table->string('arquivoNome'); // nome do arquivo
            $table->text('codigoAnexoPublico'); // código aberto para linkagem do anexo
            $table->text('codigoAnexoSecreto'); // código secreto e nome da pasta do arquivo

            $table->unsignedBigInteger('user_id'); // quem é o dono do arquivo
            $table->unsignedBigInteger('protocolo_id'); // a que protocolo o anexo faz parte

            //fk
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('protocolo_id')->references('id')->on('protocolos')->onDelete('cascade');

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
        Schema::table('anexos', function (Blueprint $table) {
            $table->dropForeign('anexos_user_id_foreign');
            $table->dropForeign('anexos_protocolo_id_foreign');
        });
        Schema::dropIfExists('anexos');
    }
}
