<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteAlumnoIdColumnToAnexos1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anexos1', function (Blueprint $table) {
            $table->dropForeign('anexos1_alumno_id_foreign');
            $table->dropColumn('alumno_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anexos1', function (Blueprint $table) {
            $table->foreignId('alumno_id')->after('titulo');
            $table->foreign('alumno_id')->references('id')->on('users');
        });
    }
}
