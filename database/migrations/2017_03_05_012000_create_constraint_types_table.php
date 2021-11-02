<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstraintTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constraint_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('code', 5);
            $table->unsignedTinyInteger('status')->default(2); // 0: off, 1: strong, 2: on
            $table->boolean('is_work');
            $table->boolean('is_single_day');
            $table->boolean('is_group_constraint');
            $table->boolean('is_day_in_schedule');
            $table->unsignedInteger('branch_id');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('constraint_types');
    }
}
