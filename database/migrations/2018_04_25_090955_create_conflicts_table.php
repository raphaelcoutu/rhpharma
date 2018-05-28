<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConflictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conflicts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('schedule_id');
            $table->unsignedInteger('department_id')->nullable();
            $table->integer('severity')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('message');
            $table->timestamps();

            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conflicts');
    }
}
