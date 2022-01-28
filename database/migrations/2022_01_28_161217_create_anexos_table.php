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
            $table->text('codigoPasta'); // pasta onde será salvo o arquivo
            $table->text('codigoAnexo'); // url completa do arquivo

            $table->unsignedBigInteger('user_id');

            //fk
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
        Schema::table('anexos', function (Blueprint $table) {
            $table->dropForeign('anexos_user_id_foreign');
        });
        Schema::dropIfExists('anexos');
    }
}
