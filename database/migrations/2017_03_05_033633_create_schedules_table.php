<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('constraint_limit_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('branch_id');
            $table->tinyInteger('status_holidays')->default(0);
            $table->tinyInteger('status_weekends')->default(0);
            $table->tinyInteger('status_friday_nights')->default(0);
            $table->tinyInteger('status_clinical_departments')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
