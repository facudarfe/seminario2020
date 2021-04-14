<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnexo1UserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anexo1_user', function (Blueprint $table) {
            $table->foreignId('anexo1_id');
            $table->foreignId('alumno_id');
            $table->boolean('aceptado')->default(true);

            $table->foreign('anexo1_id')->references('id')->on('anexos1');
            $table->foreign('alumno_id')->references('id')->on('users');

            $table->primary(['anexo1_id', 'alumno_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anexo1_user');
    }
}
