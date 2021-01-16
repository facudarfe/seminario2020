<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionesAnexos1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versiones_anexos1', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anexo_id');
            $table->text('resumen');
            $table->text('tecnologias');
            $table->text('descripcion');
            $table->foreignId('docente_id')->nullable();
            $table->timestamp('fecha_correccion')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('estado_id');

            $table->foreign('anexo_id')->references('id')->on('anexos1');
            $table->foreign('docente_id')->references('id')->on('users');
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
        Schema::dropIfExists('versiones_anexos1');
    }
}
