<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstraintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constraints', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('constrainttype_id');
            $table->boolean('weight');
            $table->text('comment');
            $table->integer('status');
            $table->integer('created_by');
            $table->integer('branch_id');
            $table->integer('number_of_occurrences');
            $table->integer('disposition');
            $table->boolean('is_day_of_week');
            $table->integer('day');
            $table->integer('day1');
            $table->string('discriminator');


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
        Schema::dropIfExists('constraints');
    }
}
