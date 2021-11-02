<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstraintTypeCriterionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constraint_type_criterion', function (Blueprint $table) {
            $table->unsignedInteger('constraint_type_id');
            $table->unsignedInteger('criterion_id');

            $table->foreign('constraint_type_id')->references('id')->on('constraint_types');
            $table->foreign('criterion_id')->references('id')->on('criteria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('constraint_type_criteria');
    }
}
