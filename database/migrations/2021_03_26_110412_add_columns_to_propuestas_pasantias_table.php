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

            $table->foreign('docente_id')->references('id')->on('users');
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
            $table->dropForeign('propuestas_pasantias_docente_id_foreign');

            $table->dropColumn('docente_id');
        });
    }
}
