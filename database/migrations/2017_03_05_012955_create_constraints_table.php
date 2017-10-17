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
            $table->unsignedInteger('user_id');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->integer('constraint_type_id');
            $table->boolean('weight');
            $table->text('comment')->nullable();
            $table->integer('status');
            $table->unsignedInteger('validated_by')->nullable();
            $table->integer('number_of_occurrences')->nullable();
//            $table->integer('disposition');
//            $table->boolean('is_day_of_week');
//            $table->integer('day');
//            $table->integer('day1');
//            $table->string('discriminator');


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
