<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFechaCorreccionColumnOnVersionesAnexos1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('versiones_anexos1', function($table){
            $table->date('fecha_correccion')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('versiones_anexos1', function($table){
            $table->timestamp('fecha_correccion')->change();
        });
    }
}
