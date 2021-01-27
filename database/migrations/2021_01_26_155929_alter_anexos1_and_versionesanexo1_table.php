<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAnexos1AndVersionesanexo1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anexos1', function($table){
            $table->foreignId('docente_id')->nullable();
            $table->foreign('docente_id')->references('id')->on('users');
        });

        Schema::table('versiones_anexos1', function($table){
            $table->dropForeign('docente_id');
            $table->dropColumn('docente_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anexos1', function($table){
            $table->dropForeign('docente_id');
            $table->dropColumn('docente_id');
        });

        Schema::table('versiones_anexos1', function($table){
            $table->foreignId('docente_id')->nullable();
            $table->foreign('docente_id')->references('id')->on('users');
        }); 
    }
}
