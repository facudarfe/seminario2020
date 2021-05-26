<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteDirectoresIdFromAnexos1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anexos1', function (Blueprint $table) {
            $table->dropForeign('anexos1_director_id_foreign');
            $table->dropForeign('anexos1_codirector_id_foreign');

            $table->dropColumn('director_id');
            $table->dropColumn('codirector_id');
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
            $table->foreignId('director_id');
            $table->foreignId('codirector_id');

            $table->foreign('director_id')->references('id')->on('users');
            $table->foreign('codirector_id')->references('id')->on('users');
        });
    }
}
