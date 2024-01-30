<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDatetimeWithoutTzToConstraintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('constraints', function (Blueprint $table) {
            $table->renameColumn('start_datetime', 'start_datetimetz');
            $table->renameColumn('end_datetime', 'end_datetimetz');
        });

        Schema::table('constraints', function (Blueprint $table) {
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
        });

        DB::statement("UPDATE constraints SET start_datetime = start_datetimetz AT TIME ZONE 'America/Montreal'");
        DB::statement("UPDATE constraints SET end_datetime = end_datetimetz AT TIME ZONE 'America/Montreal'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('constraints', function (Blueprint $table) {
            $table->dropColumn('start_datetime');
            $table->dropColumn('end_datetime');
        });

        Schema::table('constraints', function (Blueprint $table) {
            $table->renameColumn('start_datetimetz', 'start_datetime');
            $table->renameColumn('end_datetimetz', 'end_datetime');
        });
    }
}
