<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTribunalesEvaluadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tribunales_evaluadores', function (Blueprint $table) {
            $table->foreignId('anexo2_id');
            $table->foreign('anexo2_id')->references('id')->on('anexos2');
            $table->string('docente_dni', 20);
            $table->foreign('docente_dni')->references('dni')->on('docentes');
            $table->boolean('titular')->default(true);
            $table->primary(['anexo2_id', 'docente_dni']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tribunales_evaluadores');
    }
}
