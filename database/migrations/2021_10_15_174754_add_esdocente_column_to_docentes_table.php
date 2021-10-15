<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEsdocenteColumnToDocentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docentes', function (Blueprint $table) {
            $table->boolean('esDocente')->nullable()->default(false)->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docentes', function (Blueprint $table) {
            $table->dropColumn('esDocente');
        });
    }
}
