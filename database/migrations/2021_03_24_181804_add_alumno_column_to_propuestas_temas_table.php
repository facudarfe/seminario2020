<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlumnoColumnToPropuestasTemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propuestas_temas', function (Blueprint $table) {
            $table->unsignedBigInteger('alumno_id')->nullable()->unique()->after('docente_id');

            $table->foreign('alumno_id')->references('id')->on('users')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('propuestas_temas', function (Blueprint $table) {
            $table->dropForeign('propuestas_temas_alumno_id_foreign');
            $table->dropColumn('alumno_id');
        });
    }
}
