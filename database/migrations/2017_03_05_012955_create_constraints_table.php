<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedInteger('constraint_type_id');
            $table->boolean('weight');
            $table->text('comment')->nullable();
            $table->integer('status');
            $table->unsignedInteger('validated_by')->nullable();
            $table->integer('number_of_occurrences')->nullable();
            $table->integer('day')->nullable();
            $table->integer('disposition')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('constraint_type_id')->references('id')->on('constraint_types');
            $table->foreign('validated_by')->references('id')->on('users');


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
