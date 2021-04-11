<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnexos1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anexos1', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->foreignId('alumno_id');
            $table->foreignId('director_id');
            $table->foreignId('codirector_id');
            $table->foreignId('modalidad_id');
            $table->foreignId('estado_id');

            $table->foreign('alumno_id')->references('id')->on('users');
            $table->foreign('director_id')->references('id')->on('users');
            $table->foreign('codirector_id')->references('id')->on('users');
            $table->foreign('modalidad_id')->references('id')->on('modalidades');
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('anexos1');
    }
}
