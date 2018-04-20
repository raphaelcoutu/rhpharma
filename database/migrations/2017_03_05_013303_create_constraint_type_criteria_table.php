<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstraintTypeCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constraint_type_criteria', function (Blueprint $table) {
            $table->unsignedInteger('constraint_type_id');
            $table->unsignedInteger('criterion_id');

            $table->primary(['constraint_type_id', 'criterion_id']);
            $table->index(['criterion_id', 'constraint_type_id']);

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
