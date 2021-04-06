<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnexos2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anexos2', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_propuesta');
            $table->dateTime('fecha_definitiva')->nullable();
            $table->foreignId('anexo1_id');
            $table->foreign('anexo1_id')->references('id')->on('anexos1');
            $table->foreignId('estado_id');
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
        Schema::dropIfExists('anexos2');
    }
}
