<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDatetimeTzToConstraintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('constraints', function (Blueprint $table) {
            $table->dropColumn('start_datetimetz');
            $table->dropColumn('end_datetimetz');

            $table->dateTime('start_datetime')->nullable(false)->change();
            $table->dateTime('end_datetime')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('constraints', function (Blueprint $table) {
            $table->dateTime('start_datetime')->nullable(true)->change();
            $table->dateTime('end_datetime')->nullable(true)->change();

            $table->dateTimeTz('start_datetimetz')->nullable();
            $table->dateTimeTz('end_datetimetz')->nullable();
        });
    }
}
