<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPropuestasPasantiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propuestas_pasantias', function (Blueprint $table) {
            $table->foreignId('docente_id')->after('fecha_fin');
            $table->foreignId('alumno_id')->nullable()->unique()->after('docente_id');

            $table->foreign('docente_id')->references('id')->on('users');
            $table->foreign('alumno_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('propuestas_pasantias', function (Blueprint $table) {
            $table->dropForeign('docente_id');
            $table->dropForeign('alumno_id');

            $table->dropColumn('docente_id');
            $table->dropColumn('alumno_id');
        });
    }
}
