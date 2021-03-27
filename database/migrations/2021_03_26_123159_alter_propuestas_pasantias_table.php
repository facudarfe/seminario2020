<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPropuestasPasantiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propuestas_pasantias', function (Blueprint $table) {
            $table->dropColumn('fecha_inicio');
            $table->string('lugar')->after('titulo');
            $table->integer('duracion')->after('tutores');
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
            $table->date('fecha_inicio')->after('tutores');

            $table->dropColumn('lugar');
            $table->dropColumn('duracion');
        });
    }
}
