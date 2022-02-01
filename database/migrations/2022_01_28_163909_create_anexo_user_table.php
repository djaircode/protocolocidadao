<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnexoUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anexo_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('anexo_id');
            $table->index(['user_id', 'anexo_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('anexo_id')->references('id')->on('anexos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anexo_user', function (Blueprint $table) {
            $table->dropForeign('anexo_user_user_id_foreign');
            $table->dropForeign('anexo_user_anexo_id_foreign');
        });
        Schema::dropIfExists('anexo_user');
    }
}
