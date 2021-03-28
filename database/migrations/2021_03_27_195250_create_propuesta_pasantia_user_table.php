<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropuestaPasantiaUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propuesta_pasantia_user', function (Blueprint $table) {
            $table->foreignId('pasantia_id');
            $table->foreignId('user_id');
            $table->string('ruta_cv');

            $table->foreign('pasantia_id')->references('id')->on('propuestas_pasantias')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->primary(['pasantia_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('propuesta_pasantia_user');
    }
}
