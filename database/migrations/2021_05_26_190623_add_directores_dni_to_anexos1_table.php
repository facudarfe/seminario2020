<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirectoresDniToAnexos1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anexos1', function (Blueprint $table) {
            $table->string('director_dni', 10);
            $table->string('codirector_dni', 10)->nullable();

            $table->foreign('director_dni')->references('dni')->on('docentes');
            $table->foreign('codirector_dni')->references('dni')->on('docentes');
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
            $table->dropForeign('anexos1_director_dni_foreign');
            $table->dropForeign('anexos1_codirector_dni_foreign');

            $table->dropColumn('director_dni');
            $table->dropColumn('codirector_dni');
        });
    }
}
