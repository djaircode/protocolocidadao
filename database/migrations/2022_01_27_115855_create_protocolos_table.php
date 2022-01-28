<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocolosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolos', function (Blueprint $table) {
            $table->id();

            $table->text('conteudo');
            $table->string('referencia');

            // fk
            $table->unsignedBigInteger('setor_id');
            $table->unsignedBigInteger('protocolo_tipo_id');
            $table->unsignedBigInteger('protocolo_situacao_id');
            $table->unsignedBigInteger('user_id');

            $table->dateTime('concluido_em')->nullable();
            $table->text('concluido_mensagem')->nullable();
            $table->enum('concluido', ['s', 'n']);

            $table->foreign('setor_id')->references('id')->on('setors')->onDelete('cascade');
            $table->foreign('protocolo_tipo_id')->references('id')->on('protocolo_tipos')->onDelete('cascade');
            $table->foreign('protocolo_situacao_id')->references('id')->on('protocolo_situacaos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
         Schema::table('protocolos', function (Blueprint $table) {
            $table->dropForeign('protocolos_setor_id_foreign');
            $table->dropForeign('protocolos_protocolo_tipo_id_foreign');
            $table->dropForeign('protocolos_protocolo_situacao_id_foreign');
            $table->dropForeign('protocolos_user_id_foreign');
        });

        Schema::dropIfExists('protocolos');
    }
}
